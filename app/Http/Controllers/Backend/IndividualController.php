<?php

namespace App\Http\Controllers\Backend;

use App\Decorators\Phone;
use App\Http\Requests\Backend\Individual\IndividualRequest;
use App\Models\Individual;
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
 * Class IndividualController
 * @package App\Http\Controllers\Backend
 */
class IndividualController extends BackendController
{

    /**
     * @var string
     */
    public $module = "individual";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'individual.read',
        'show'            => 'individual.read',
        'edit'            => 'individual.read',
        'update'          => 'individual.write',
        'destroy'         => 'individual.delete'
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.individuals'));

        $this->breadcrumbs(trans('labels.individuals'), route('admin.individual.index'));

        $this->middleware('slug.set', ['only' => ['update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /individual
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Individual::select(
                'id',
                'email',
                'phone',
                'price'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'individuals.id', '=', '$1')
                ->filterColumn('email', 'where', 'email', 'LIKE', '%$1%')
                ->filterColumn('phone', 'where', 'phone', 'LIKE', '%$1%')
                ->filterColumn('price', 'where', 'price', 'LIKE', '%$1%')
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => $this->module, 'basket_link' => true]
                        )->render();
                    }
                )
                ->removeColumn('image')
                ->removeColumn('text')
                ->make();
        }

        $this->data('page_title', trans('labels.individuals'));
        $this->breadcrumbs(trans('labels.individuals_list'));

        return $this->render('views.individual.index');
    }

    /**
     * Display the specified resource.
     * GET /individual/{id}
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
     * GET /individual/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Individual::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.individual.index');
        }

        $this->data('page_title', '"'.$model->email.'(' . $model->phone . ')"');

        $this->breadcrumbs(trans('labels.individual_editing'));

        return $this->render('views.individual.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /individual/{id}
     *
     * @param  int              $id
     * @param IndividualRequest $request
     *
     * @return \Response
     */
    public function update($id, IndividualRequest $request)
    {
        try {
            $model = Individual::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.individual.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.individual.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /individual/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Individual::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.individual.index');
    }
}