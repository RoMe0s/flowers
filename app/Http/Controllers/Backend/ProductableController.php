<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Productable\ProductableRequest;
use App\Models\Bouquet;
use App\Models\Product;
use App\Models\Productable;
use App\Models\Set;
use App\Models\SetTranslation;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;

/**
 * Class ProductableController
 * @package App\Http\Controllers\Backend
 */
class ProductableController extends BackendController
{

    use AjaxFieldsChangerTrait;

 

    /**
     * @var string
     */
    public $module = "productable";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'productable.read',
        'create'          => 'productable.create',
        'store'           => 'productable.create',
        'show'            => 'productable.read',
        'edit'            => 'productable.read',
        'update'          => 'productable.write',
        'destroy'         => 'productable.delete',
        'ajaxFieldChange' => 'productable.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.productable'));

        $this->breadcrumbs(trans('labels.productable'), route('admin.productable.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /productable
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Productable::select(
                'id',
		'productable_type',
		'status',
		'position'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('productables.id', 'where', 'productables.id', '=', '$1')
                ->filterColumn('productable_type', 'where', 'productable_type', 'LIKE', '%$1%')
                ->editColumn(
                    'productable_type',
                    function ($model) {

                        $types = Productable::getTypes();

                        return $types[$model->productable_type];

                    }
                )
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
                ->make();
        }

        $this->data('page_title', trans('labels.productable'));
        $this->breadcrumbs(trans('labels.productable_list'));

        return $this->render('views.productable.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /productable/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Productable);

        $this->data('page_title', trans('labels.productable_create'));

        $this->breadcrumbs(trans('labels.productable_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('views.productable.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /productable
     *
     * @param ProductableRequest $request
     *
     * @return \Response
     */
    public function store(ProductableRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {

            $model = new Productable($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.productable.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /productable/{id}
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
     * GET /productable/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Productable::whereId($id)->firstOrFail();

            $this->data('page_title', trans('labels.productable_editing'));

            $this->breadcrumbs(trans('labels.productable_editing'));

            $this->_fillAdditionalTemplateData($model);

            return $this->render('views.productable.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.productable.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /productable/{id}
     *
     * @param  int              $id
     * @param ProductableRequest $request
     *
     * @return \Response
     */
    public function update($id, ProductableRequest $request)
    {
        try {
            $model = Productable::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();

            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.productable.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());
        }

        return Redirect::back()->withInput($input);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /productable/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Productable::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.productable.index');
    }

    private function _fillAdditionalTemplateData($model = null) {

        $types = Productable::getTypes();

        $this->data('types', $types);

        $productables = array();

        if($model) {


            $class = $model->productable_type;

            $key = explode("\\", $class);

            $key = array_pop($key);

            $key = strtolower($key) . '_id';

            $class .= 'Translation';

            $productables = $class::lists('name', $key)->toArray();

        }

        $this->data('productables', $productables);

    }

    public function ajaxLoad(Request $request) {

        $class = $request->get('type', null);

        if($class) {

            $used_ids = Productable::where('productable_type', $class)->lists('productable_id')->toArray();

            $key = explode("\\", $class);

            $key = array_pop($key);

            $key = strtolower($key) . '_id';

            $class .= 'Translation';

            $productables = $class::whereNotIn($key, $used_ids)->lists('name', $key)->toArray();

            $html = view('productable.partials.productable_id')->with(['productables' => $productables])->render();

            return ['html' => $html];

        }

    }

}
