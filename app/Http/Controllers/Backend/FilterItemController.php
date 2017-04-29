<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\FilterItem\FilterItemRequest;
use App\Models\FilterItem;
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
 * Class FilterItemController
 * @package App\Http\Controllers\Backend
 */
class FilterItemController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "filteritem";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'filteritem.read',
        'create'          => 'filteritem.create',
        'store'           => 'filteritem.create',
        'show'            => 'filteritem.read',
        'edit'            => 'filteritem.read',
        'update'          => 'filteritem.write',
        'destroy'         => 'filteritem.delete',
        'ajaxFieldChange' => 'filteritem.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.filteritem'));

        $this->breadcrumbs(trans('labels.filteritem'), route('admin.filteritem.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /filteritem
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = FilterItem::joinTranslations('filter_items', 'filter_item_translations', 'id', 'filter_item_id')
                ->select(
                    'filter_items.id',
                    'title',
                    'value',  
                    'status',
                    'position'
                );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'id', '=', '$1')
                ->filterColumn('value', 'where', 'value', 'LIKE', '%$1%')
                ->filterColumn('title', 'where', 'title', 'LIKE', '%$1%')
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
                            ['model' => $model, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('translations')
                ->make();
        }

        $this->data('page_title', trans('labels.filteritem'));
        $this->breadcrumbs(trans('labels.filteritem_list'));

        return $this->render('views.filteritem.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /filteritem/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new FilterItem);

        $this->data('page_title', trans('labels.filteritem_create'));

        $this->_fillAdditionalTemplateData();

        $this->breadcrumbs(trans('labels.filteritem_create'));

        return $this->render('views.filteritem.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /filteritem
     *
     * @param FilterItemRequest $request
     *
     * @return \Response
     */
    public function store(FilterItemRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {

            $model = new FilterItem($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.filteritem.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /filteritem/{id}
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
     * GET /filteritem/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = FilterItem::whereId($id)->firstOrFail();

            $this->data('page_title', trans('labels.filteritem_editing'));

            $this->_fillAdditionalTemplateData();

            $this->breadcrumbs(trans('labels.filteritem_editing'));

            return $this->render('views.filteritem.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.filteritem.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /filteritem/{id}
     *
     * @param  int              $id
     * @param FilterItemRequest $request
     *
     * @return \Response
     */
    public function update($id, FilterItemRequest $request)
    {
        try {
            $model = FilterItem::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();

            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.filteritem.index');
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
     * DELETE /filteritem/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = FilterItem::findOrFail($id);

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

        return Redirect::route('admin.filteritem.index');
    }

    private function _fillAdditionalTemplateData() {

        $this->data('types', FilterItem::getTypes());

    }

}
