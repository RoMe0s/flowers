<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Code\CodeUpdateRequest;
use App\Http\Requests\Backend\Code\CodeCreateRequest;
use App\Models\Code;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Event;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;

/**
 * Class CodeController
 * @package App\Http\Controllers\Backend
 */
class CodeController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "code";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'code.read',
        'create'          => 'code.create',
        'store'           => 'code.create',
        'show'            => 'code.read',
        'edit'            => 'code.read',
        'update'          => 'code.write',
        'destroy'         => 'code.delete',
        'ajaxFieldChange' => 'code.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.codes'));

        $this->breadcrumbs(trans('labels.codes'), route('admin.code.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /code
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Code::select(
                'id',
                'code',
                'discount',
                'date',
                'status'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'codes.id', '=', '$1')
                ->filterColumn('code', 'where', 'code', 'LIKE', '%$1%')
                ->filterColumn('discount', 'where', 'discount', '=', '$1')
                ->filterColumn('date', 'where', 'date', 'LIKE', '%$1%')
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

        $this->data('page_title', trans('labels.codes'));
        $this->breadcrumbs(trans('labels.codes_list'));

        return $this->render('views.code.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /code/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Code);

        $this->data('page_title', trans('labels.code_create'));

        $this->breadcrumbs(trans('labels.code_create'));

        return $this->render('views.code.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /code
     *
     * @param CodeCreateRequest $request
     *
     * @return \Response
     */
    public function store(CodeCreateRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Code($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.code.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /code/{id}
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
     * GET /code/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Code::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.code.index');
        }

        $this->data('page_title', '"'.$model->code.'"');

        $this->breadcrumbs(trans('labels.code_editing'));

        return $this->render('views.code.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /code/{id}
     *
     * @param  int              $id
     * @param CodeUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, CodeUpdateRequest $request)
    {
        try {
            $model = Code::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.code.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.code.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /code/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {

            $model = Code::findOrFail($id);

            if (!$model->delete()) {

                FlashMessages::add("error", trans("messages.destroy_error"));

            } else {

                FlashMessages::add('success', trans("messages.destroy_ok"));

            }
        } catch (ModelNotFoundException $e) {

            FlashMessages::add('error', trans('messages.record_not_found'));

        }

        return Redirect::route('admin.code.index');
    }
}