<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Box\BoxRequest;
use App\Models\Box;
use App\Models\CategoryTranslation;
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
 * Class BoxController
 * @package App\Http\Controllers\Backend
 */
class BoxController extends BackendController
{

    /**
     * @var string
     */
    public $module = "box";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'box.read',
        'create'          => 'box.create',
        'store'           => 'box.create',
        'show'            => 'box.read',
        'edit'            => 'box.read',
        'update'          => 'box.write',
        'destroy'         => 'box.delete',
        'ajaxFieldChange' => 'box.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.boxes'));

        $this->breadcrumbs(trans('labels.boxes'), route('admin.box.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /box
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Box::with('category')->joinTranslations('boxes', 'box_translations')->select(
                'boxes.id',
                'box_translations.title',
                'category_id',
                'boxes.length',
                'boxes.width'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'boxes.id', '=', '$1')
                ->filterColumn('box_translations.name', 'where', 'box_translations.name', 'LIKE', '%$1%')
                ->filterColumn('boxes.length', 'where', 'boxes.length', 'LIKE', '%$1%')
                ->filterColumn('boxes.width', 'where', 'boxes.width', 'LIKE', '%$1%')
                ->filterColumn('boxes.category_id', 'where', 'boxes.category_id', 'LIKE', '%$1%')
                ->editColumn(
                    'category_id',
                    function ($model) {
                        return isset($model->category) ? $model->category->name : trans('labels.no');
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
                ->removeColumn('image')
                ->removeColumn('category')
                ->make();
        }

        $this->data('page_title', trans('labels.boxes'));
        $this->breadcrumbs(trans('labels.boxes_list'));

        return $this->render('views.box.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /box/create
     *
     * @return Response
     */
    public function create()
    {
        $this->_fillAdditionTemplateData();

        $this->data('model', new Box);

        $this->data('page_title', trans('labels.box_create'));

        $this->breadcrumbs(trans('labels.box_create'));

        return $this->render('views.box.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /box
     *
     * @param BoxRequest $request
     *
     * @return \Response
     */
    public function store(BoxRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Box($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.box.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /box/{id}
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
     * GET /box/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Box::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.box.index');
        }

        $this->data('page_title', '"'.$model->title.'"');

        $this->breadcrumbs(trans('labels.box_editing'));

        $this->_fillAdditionTemplateData();

        return $this->render('views.box.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /box/{id}
     *
     * @param  int              $id
     * @param BoxRequest $request
     *
     * @return \Response
     */
    public function update($id, BoxRequest $request)
    {
        try {
            $model = Box::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.box.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.box.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /box/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Box::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.box.index');
    }

    private function _fillAdditionTemplateData()
    {
        $this->data('categories', CategoryTranslation::lists('name', 'category_id')->toArray());
    }
}