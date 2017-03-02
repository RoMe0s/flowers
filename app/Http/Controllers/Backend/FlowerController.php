<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Flower\FlowerRequest;
use App\Models\ColorTranslation;
use App\Models\Flower;
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
use App\Traits\Controllers\AjaxFieldsChangerTrait;

/**
 * Class FlowerController
 * @package App\Http\Controllers\Backend
 */
class FlowerController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "flower";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'flower.read',
        'create'          => 'flower.create',
        'store'           => 'flower.create',
        'show'            => 'flower.read',
        'edit'            => 'flower.read',
        'update'          => 'flower.write',
        'destroy'         => 'flower.delete',
        'ajaxFieldChange' => 'flower.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.flowers'));

        $this->breadcrumbs(trans('labels.flowers'), route('admin.flower.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /flower
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Flower::joinTranslations('flowers', 'flower_translations')->select(
                'flowers.id',
                'flower_translations.title',
                'flowers.status',
                'flowers.position'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'flowers.id', '=', '$1')
                ->filterColumn('flower_translations.title', 'where', 'flower_translations.title', 'LIKE', '%$1%')
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
                ->make();
        }

        $this->data('page_title', trans('labels.flowers'));
        $this->breadcrumbs(trans('labels.flowers_list'));

        return $this->render('views.flower.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /flower/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Flower);

        $this->data('page_title', trans('labels.flower_create'));

        $this->_fillAdditionTemplateData();

        $this->breadcrumbs(trans('labels.flower_create'));

        return $this->render('views.flower.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /flower
     *
     * @param FlowerRequest $request
     *
     * @return \Response
     */
    public function store(FlowerRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Flower($input);

            $model->save();

            $this->_proccessColors($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.flower.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /flower/{id}
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
     * GET /flower/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Flower::with('translations', 'colors')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.flower.index');
        }

        $this->_fillAdditionTemplateData();

        $this->data('page_title', '"'.$model->title.'"');

        $this->breadcrumbs(trans('labels.flower_editing'));

        return $this->render('views.flower.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /flower/{id}
     *
     * @param  int              $id
     * @param FlowerRequest $request
     *
     * @return \Response
     */
    public function update($id, FlowerRequest $request)
    {
        try {
            $model = Flower::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.flower.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            $this->_proccessColors($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.flower.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /flower/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Flower::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.flower.index');
    }

    private function _proccessColors(Flower $model) {

        $new_colors = request('colors');

        $old_colors = $model->colors->lists('color_id')->toArray();

        $remove_colors = array_diff($old_colors, $new_colors);

        $model->colors()->detach($remove_colors);

        $model->colors()->attach(array_diff($new_colors, $old_colors));

    }

    private function _fillAdditionTemplateData() {

        $this->data('colors', ColorTranslation::lists('title','color_id')->toArray());

    }
}