<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Sale\SaleCreateRequest;
use App\Http\Requests\Backend\Sale\SaleUpdateRequest;
use App\Models\Sale;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use App\Traits\Controllers\SaveImagesTrait;
use Datatables;
use DB;
use Event;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;

/**
 * Class SaleController
 * @package App\Http\Controllers\Backend
 */
class SaleController extends BackendController
{

    use AjaxFieldsChangerTrait;

    use SaveImagesTrait;

    /**
     * @var string
     */
    public $module = "sale";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'sale.read',
        'create'          => 'sale.create',
        'store'           => 'sale.create',
        'show'            => 'sale.read',
        'edit'            => 'sale.read',
        'update'          => 'sale.write',
        'destroy'         => 'sale.delete',
        'ajaxFieldChange' => 'sale.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.sales'));

        $this->breadcrumbs(trans('labels.sales'), route('admin.sale.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /sale
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Sale::joinTranslations('sales')->select(
                'sales.id',
                'sale_translations.name',
                'sales.price',
                'sales.publish_at',
                'sales.status',
                'sales.position',
                'sales.slug'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'sales.id', '=', '$1')
                ->filterColumn('sale_translations.name', 'where', 'sale_translations.name', 'LIKE', '%$1%')
                ->filterColumn('price', 'where', 'price', 'LIKE', '%$1%')
                ->filterColumn('publish_at', 'where', 'publish_at', 'LIKE', '%$1%')
                ->editColumn(
                    'status',
                    function ($model) {
                        return view(
                            'partials.datatables.toggler',
                            ['model' => $model, 'type' => $this->module, 'field' => 'status']
                        )->render();
                    }
                )
                ->editColumn(
                    'position',
                    function ($model) {
                        return view(
                            'partials.datatables.text_input',
                            ['model' => $model, 'type' => $this->module, 'field' => 'position']
                        )->render();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => $this->module, 'basket_link' => true]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('short_content')
                ->removeColumn('content')
                ->removeColumn('meta_keywords')
                ->removeColumn('meta_title')
                ->removeColumn('meta_description')
                ->removeColumn('image')
                ->removeColumn('slug')
                ->removeColumn('images')
                ->make();
        }

        $this->data('page_title', trans('labels.sales'));
        $this->breadcrumbs(trans('labels.sales_list'));

        return $this->render('views.sale.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /sale/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Sale);

        $this->data('page_title', trans('labels.sale_create'));

        $this->breadcrumbs(trans('labels.sale_create'));

        return $this->render('views.sale.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /sale
     *
     * @param SaleCreateRequest $request
     *
     * @return \Response
     */
    public function store(SaleCreateRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Sale($input);

            $model->save();

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.sale.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /sale/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /sale/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Sale::with('translations')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.sale.index');
        }

        $this->data('page_title', '"'.$model->name.'"');

        $this->breadcrumbs(trans('labels.sale_editing'));

        return $this->render('views.sale.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /sale/{id}
     *
     * @param  int              $id
     * @param SaleUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, SaleUpdateRequest $request)
    {
        try {
            $model = Sale::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.sale.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.sale.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /sale/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {

            $model = Sale::findOrFail($id);

            if (!$model->delete()) {

                FlashMessages::add("error", trans("messages.destroy_error"));

            } else {

                FlashMessages::add('success', trans("messages.destroy_ok"));

            }
        } catch (ModelNotFoundException $e) {

            FlashMessages::add('error', trans('messages.record_not_found'));

        }

        return Redirect::route('admin.sale.index');
    }
}