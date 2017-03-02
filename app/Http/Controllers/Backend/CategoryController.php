<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Category\CategoryRequest;
use App\Models\Category;
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
 * Class CategoryController
 * @package App\Http\Controllers\Backend
 */
class CategoryController extends BackendController
{
    /**
     * @var string
     */
    public $module = "category";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'category.read',
        'create'          => 'category.create',
        'store'           => 'category.create',
        'show'            => 'category.read',
        'edit'            => 'category.read',
        'update'          => 'category.write',
        'destroy'         => 'category.delete',
        'ajaxFieldChange' => 'category.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.categories'));

        $this->breadcrumbs(trans('labels.categories'), route('admin.category.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /category
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Category::joinTranslations('categories', 'category_translations')->select(
                'categories.id',
                'category_translations.title'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'categories.id', '=', '$1')
                ->filterColumn('category_translations.title', 'where', 'category_translations.title', 'LIKE', '%$1%')
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
                ->make();
        }

        $this->data('page_title', trans('labels.categories'));
        $this->breadcrumbs(trans('labels.categories_list'));

        return $this->render('views.category.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /category/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Category);

        $this->data('page_title', trans('labels.category_create'));

        $this->breadcrumbs(trans('labels.category_create'));

        return $this->render('views.category.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /category
     *
     * @param CategoryRequest $request
     *
     * @return \Response
     */
    public function store(CategoryRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Category($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.category.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /category/{id}
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
     * GET /category/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.category.index');
        }

        $this->data('page_title', '"'.$model->title.'"');

        $this->breadcrumbs(trans('labels.category_editing'));

        return $this->render('views.category.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /category/{id}
     *
     * @param  int              $id
     * @param CategoryRequest $request
     *
     * @return \Response
     */
    public function update($id, CategoryRequest $request)
    {
        try {
            $model = Category::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.category.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.category.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /category/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Category::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.category.index');
    }
}