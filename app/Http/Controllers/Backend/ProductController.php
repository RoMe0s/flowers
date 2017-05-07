<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Product\ProductUpdateRequest;
use App\Http\Requests\Backend\Product\ProductCreateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryTranslation;
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
 * Class ProductController
 * @package App\Http\Controllers\Backend
 */
class ProductController extends BackendController
{

    use AjaxFieldsChangerTrait;

    use SaveImagesTrait;

    /**
     * @var string
     */
    public $module = "product";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'product.read',
        'create'          => 'product.create',
        'store'           => 'product.create',
        'show'            => 'product.read',
        'edit'            => 'product.read',
        'update'          => 'product.write',
        'destroy'         => 'product.delete',
        'ajaxFieldChange' => 'product.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.product'));

        $this->breadcrumbs(trans('labels.product'), route('admin.product.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /product
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Product::with(['translations' ,'category'])->joinTranslations('products', 'product_translations')->select(
                'products.id',
                'product_translations.name',
                'products.price',
                'products.status',
                'products.position',
                'products.slug',
                'products.category_id'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('products.id', 'where', 'products.id', '=', '$1')
                ->filterColumn('product_translations.name', 'where', 'product_translations.name', 'LIKE', '%$1%')
                ->filterColumn('products.price', 'where', 'products.price', 'LIKE', '%$1%')
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
                ->removeColumn('parent_id')
                ->removeColumn('slug')
                ->removeColumn('image')
                ->removeColumn('size')
                ->removeColumn('translations')
                ->removeColumn('images')
                ->removeColumn('category')
                ->removeColumn('category_id')
                ->make();
        }

        $this->data('page_title', trans('labels.product'));
        $this->breadcrumbs(trans('labels.product_list'));

        return $this->render('views.product.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /product/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Product);

        $this->data('page_title', trans('labels.product_create'));

        $this->breadcrumbs(trans('labels.product_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('views.product.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /product
     *
     * @param ProductCreateRequest $request
     *
     * @return \Response
     */
    public function store(ProductCreateRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {

            $model = new Product($input);

            $model->save();

            $this->_proccessCategories($model);

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.product.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /product/{id}
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
     * GET /product/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Product::with('translations')->whereId($id)->firstOrFail();

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.product_editing'));

            $this->_fillAdditionalTemplateData();

            return $this->render('views.product.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.product.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /product/{id}
     *
     * @param  int              $id
     * @param ProductUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, ProductUpdateRequest $request)
    {
        try {
            $model = Product::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();

            $model->fill($input);

            $model->update();

            $this->_proccessCategories($model);

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.product.index');
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
     * DELETE /product/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Product::findOrFail($id);

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

        return Redirect::route('admin.product.index');
    }

    private function _fillAdditionalTemplateData() {

        $this->data('categories', CategoryTranslation::lists('name', 'category_id')->toArray());

        $this->data('direct_categories', Category::where('type', (string)Product::class)->joinTranslations('categories')->lists('name', 'category_id')->toArray());

    }

    private function _proccessCategories(Product $model) {
        $new = request('categories');

        $old = $model->categories->lists('category_id')->toArray();

        $remove = array_diff($old, $new);

        $model->categories()->detach($remove);

        $model->categories()->attach(array_diff($new, $old));
    }

    public function find(Request $request) {

        $query = $request->get('query', null);

        $classes = [
            "App\\Models\\Product" => 'products',
            "App\\Models\\Set" => 'sets',
            "App\\Models\\Bouquet" => 'bouquets',
            "App\\Models\\Sale" => 'sales',
            "App\\Models\\Subscription" => 'subscriptions'
        ];

        $names = [
            'Product' => 'Подарок',
            'Set' => 'Набор',
            'Bouquet' => 'Букет',
            'Sale' => 'Акция',
            'Subscription' => 'Подписка'
        ];

        $products = collect();

        foreach($classes as $class => $table) {

            $field = 'name';

            $field = $class == "App\\Models\\Subscription" ? 'title' : $field;

            $result = $class::joinTranslations($table)
                ->where($field, 'LIKE', "%$query%")
                ->get();

            $products = $products->merge($result);

        }

        $html = view('order.modals.product')->with(['products' => $products, 'names' => $names])->render();

        return ['status' => 'success', 'html' => $html];

    }
}