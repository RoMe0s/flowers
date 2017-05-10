<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Bouquet\BouquetCreateRequest;
use App\Http\Requests\Backend\Bouquet\BouquetUpdateRequest;
use App\Models\Bouquet;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\FlowerTranslation;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use App\Traits\Controllers\SaveImagesTrait;
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
 * Class BouquetController
 * @package App\Http\Controllers\Backend
 */
class BouquetController extends BackendController
{

    use AjaxFieldsChangerTrait;

    use SaveImagesTrait;

    /**
     * @var string
     */
    public $module = "bouquet";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'bouquet.read',
        'create'          => 'bouquet.create',
        'store'           => 'bouquet.create',
        'show'            => 'bouquet.read',
        'edit'            => 'bouquet.read',
        'update'          => 'bouquet.write',
        'destroy'         => 'bouquet.delete',
        'ajaxFieldChange' => 'bouquet.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.bouquet'));

        $this->breadcrumbs(trans('labels.bouquet'), route('admin.bouquet.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /bouquet
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Bouquet::with(['translations', 'category'])->joinTranslations('bouquets', 'bouquet_translations')->select(
                'bouquets.id',
                'bouquets.image',
                'bouquet_translations.name',
                'bouquets.price',
                'bouquets.category_id',
                'bouquets.status',
                'bouquets.position',
                'bouquets.slug'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('bouquets.id', 'where', 'bouquets.id', '=', '$1')
                ->filterColumn('bouquet_translations.name', 'where', 'bouquet_translations.name', 'LIKE', '%$1%')
                ->filterColumn('bouquets.price', 'where', 'bouquets.price', 'LIKE', '%$1%')
                ->filterColumn('bouquets.type_id', 'where', 'bouquets.type_id', 'LIKE', '%$1%')
                ->editColumn(
                    'image',
                    function ($model) {
                        return $model->image ? "<a href='{$model->image}' data-lightbox='{$model->id}' class='text-center col-xs-12'><img src='{$model->image}' style='max-width: 75px; max-height: 75px;' /></a>" : trans('labels.no');
                    }
                )
                ->editColumn(
                    'category_id',
                    function ($model) {
                        return isset($model->category) ? $model->category->name : trans('labels.no');
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
                            ['model' => $model, 'front_link' => true, 'type' => $this->module, 'basket_link' => true]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('short_content')
                ->removeColumn('content')
                ->removeColumn('meta_keywords')
                ->removeColumn('meta_title')
                ->removeColumn('meta_description')
                ->removeColumn('translations')

                ->removeColumn('category')
                ->removeColumn('slug')
                ->removeColumn('size')
                ->removeColumn('count')
                ->removeColumn('images')
                ->make();
        }

        $this->data('page_title', trans('labels.bouquet'));
        $this->breadcrumbs(trans('labels.bouquet_list'));

        return $this->render('views.bouquet.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /bouquet/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Bouquet);

        $this->data('page_title', trans('labels.bouquet_create'));

        $this->breadcrumbs(trans('labels.bouquet_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('views.bouquet.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /bouquet
     *
     * @param BouquetCreateRequest $request
     *
     * @return \Response
     */
    public function store(BouquetCreateRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {

            $model = new Bouquet($input);

            $model->save();

            $this->_proccessFlowers($model);

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.bouquet.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /bouquet/{id}
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
     * GET /bouquet/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Bouquet::with('translations')->whereId($id)->firstOrFail();

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.bouquet_editing'));

            $this->_fillAdditionalTemplateData();

            return $this->render('views.bouquet.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.bouquet.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /bouquet/{id}
     *
     * @param  int              $id
     * @param BouquetUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, BouquetUpdateRequest $request)
    {
        try {
            $model = Bouquet::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();

            $model->fill($input);

            $model->update();

            $this->_proccessFlowers($model);

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.bouquet.index');
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
     * DELETE /bouquet/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Bouquet::findOrFail($id);

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

        return Redirect::route('admin.bouquet.index');
    }

    private function _fillAdditionalTemplateData() {

        $this->data('flowers', FlowerTranslation::lists('title', 'flower_id')->toArray());

        $this->data('categories', Category::where('type', (string)Bouquet::class)->joinTranslations('categories')->lists('category_translations.name', 'categories.id')->toArray());

    }

    private function _proccessFlowers(Bouquet $model) {
/*        $new = request('flowers');

        $old = $model->flowers->lists('flower_id')->toArray();

        $remove = array_diff($old, $new);

        $model->flowers()->detach($remove);

        $model->flowers()->attach(array_diff($new, $old));*/

        $model->flowers()->detach();

        $model->flowers()->attach(request('flowers', []));
    }
}