<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Type\TypeRequest;
use App\Models\Type;
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
 * Class TypeController
 * @package App\Http\Controllers\Backend
 */
class TypeController extends BackendController
{
    /**
     * @var string
     */
    public $module = "type";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'type.read',
        'create'          => 'type.create',
        'store'           => 'type.create',
        'show'            => 'type.read',
        'edit'            => 'type.read',
        'update'          => 'type.write',
        'destroy'         => 'type.delete',
        'ajaxFieldChange' => 'type.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.types'));

        $this->breadcrumbs(trans('labels.types'), route('admin.type.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /type
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Type::joinTranslations('types', 'type_translations')->select(
                'types.id',
                'type_translations.title'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'types.id', '=', '$1')
                ->filterColumn('type_translations.title', 'where', 'type_translations.title', 'LIKE', '%$1%')
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

        $this->data('page_title', trans('labels.types'));
        $this->breadcrumbs(trans('labels.types_list'));

        return $this->render('views.type.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /type/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Type);

        $this->data('page_title', trans('labels.type_create'));

        $this->breadcrumbs(trans('labels.type_create'));

        return $this->render('views.type.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /type
     *
     * @param TypeRequest $request
     *
     * @return \Response
     */
    public function store(TypeRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Type($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.type.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /type/{id}
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
     * GET /type/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Type::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.type.index');
        }

        $this->data('page_title', '"'.$model->title.'"');

        $this->breadcrumbs(trans('labels.type_editing'));

        return $this->render('views.type.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /type/{id}
     *
     * @param  int              $id
     * @param TypeRequest $request
     *
     * @return \Response
     */
    public function update($id, TypeRequest $request)
    {
        try {
            $model = Type::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.type.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.type.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /type/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Type::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.type.index');
    }
}