<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Color\ColorRequest;
use App\Models\Color;
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
 * Class ColorController
 * @package App\Http\Controllers\Backend
 */
class ColorController extends BackendController
{
    /**
     * @var string
     */
    public $module = "color";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'color.read',
        'create'          => 'color.create',
        'store'           => 'color.create',
        'show'            => 'color.read',
        'edit'            => 'color.read',
        'update'          => 'color.write',
        'destroy'         => 'color.delete',
        'ajaxFieldChange' => 'color.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.colors'));

        $this->breadcrumbs(trans('labels.colors'), route('admin.color.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /color
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Color::joinTranslations('colors')->select(
                'colors.id',
                'colors.hex',
                'color_translations.title'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'colors.id', '=', '$1')
                ->filterColumn('color_translations.title', 'where', 'color_translations.title', 'LIKE', '%$1%')
                ->filterColumn('hex', 'where', 'hex', 'LIKE', '%$1%')
                ->editColumn(
                    'hex',
                    function($model) {
                        return "<input type='color' disabled='true' value='{$model->hex}' style='width: 100%'/>";
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

        $this->data('page_title', trans('labels.colors'));
        $this->breadcrumbs(trans('labels.color_list'));

        return $this->render('views.color.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /color/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Color);

        $this->data('page_title', trans('labels.color_create'));

        $this->breadcrumbs(trans('labels.color_create'));

        return $this->render('views.color.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /color
     *
     * @param ColorRequest $request
     *
     * @return \Response
     */
    public function store(ColorRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Color($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.color.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /color/{id}
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
     * GET /color/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Color::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.color.index');
        }

        $this->data('page_title', '"'.$model->title.'"');

        $this->breadcrumbs(trans('labels.color_editing'));

        return $this->render('views.color.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /color/{id}
     *
     * @param  int              $id
     * @param ColorRequest $request
     *
     * @return \Response
     */
    public function update($id, ColorRequest $request)
    {
        try {
            $model = Color::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.color.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.color.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /color/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Color::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.color.index');
    }
}