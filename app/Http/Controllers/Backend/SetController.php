<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Set\SetUpdateRequest;
use App\Http\Requests\Backend\Set\SetCreateRequest;
use App\Models\BoxTranslation;
use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\FlowerTranslation;
use App\Models\Set;
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
 * Class SetController
 * @package App\Http\Controllers\Backend
 */
class SetController extends BackendController
{

    use AjaxFieldsChangerTrait;

    use SaveImagesTrait;

    /**
     * @var string
     */
    public $module = "set";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'set.read',
        'create'          => 'set.create',
        'store'           => 'set.create',
        'show'            => 'set.read',
        'edit'            => 'set.read',
        'update'          => 'set.write',
        'destroy'         => 'set.delete',
        'ajaxFieldChange' => 'set.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.set'));

        $this->breadcrumbs(trans('labels.set'), route('admin.set.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /set
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Set::with(['box', 'box.category'])->joinTranslations('sets', 'set_translations')->select(
                'sets.id',
                'sets.image',
                'set_translations.name',
                'sets.price',
                'sets.count',
                'sets.status',
                'sets.position',
                'sets.box_id',
                'sets.slug'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('sets.id', 'where', 'sets.id', '=', '$1')
                ->filterColumn('set_translations.name', 'where', 'set_translations.name', 'LIKE', '%$1%')
                ->filterColumn('sets.count', 'where', 'sets.count', '=', '$1')
                ->filterColumn('sets.price', 'where', 'sets.price', 'LIKE', '%$1%')
                ->editColumn(
                    'image',
                    function ($model) {
                        return $model->image ? "<div class='text-center'><img src='{$model->image}' style='max-width: 75px; max-height: 75px;' /></div>" : trans('labels.no');
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

                ->removeColumn('box')
                ->removeColumn('box_id')
                ->removeColumn('slug')
                ->removeColumn('category_id')
                ->removeColumn('images')
                ->make();
        }

        $this->data('page_title', trans('labels.set'));
        $this->breadcrumbs(trans('labels.set_list'));

        return $this->render('views.set.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /set/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new Set);

        $this->data('page_title', trans('labels.set_create'));

        $this->breadcrumbs(trans('labels.set_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('views.set.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /set
     *
     * @param SetCreateRequest $request
     *
     * @return \Response
     */
    public function store(SetCreateRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {

            $model = new Set($input);

            $model->save();

            $this->_proccessFlowers($model);

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.set.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /set/{id}
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
     * GET /set/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Set::with('translations', 'flowers')->whereId($id)->firstOrFail();

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.set_editing'));

            $this->_fillAdditionalTemplateData();

            return $this->render('views.set.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.set.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /set/{id}
     *
     * @param  int              $id
     * @param SetUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, SetUpdateRequest $request)
    {
//        try {
            $model = Set::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();

            $model->fill($input);

            $model->update();

            $this->_proccessFlowers($model);

            $this->_processImages($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.set.index');
/*        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());
        }*/

        return Redirect::back()->withInput($input);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /set/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Set::findOrFail($id);

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

        return Redirect::route('admin.set.index');
    }

    private function _fillAdditionalTemplateData() {

        $this->data('flowers', FlowerTranslation::lists('title', 'flower_id')->toArray());

        $boxes = array();

        foreach(Category::where('type', (string)Set::class)->with('boxes')->get() as $item) {
            $boxes[$item->name] = $item->boxes->lists('title', 'id')->toArray();
        }

        $this->data('boxes', $boxes);

    }

    private function _proccessFlowers(Set $model) {
/*        $new = request('flowers');

        $old = $model->flowers->lists('flower_id')->toArray();

        $remove = array_diff($old, $new);

        $model->flowers()->detach($remove);

        $model->flowers()->attach(array_diff($new, $old));*/

        $model->flowers()->detach();

        $model->flowers()->attach(request('flowers', []));
    }
}