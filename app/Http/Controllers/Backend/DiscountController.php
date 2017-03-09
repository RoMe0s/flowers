<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Discount\DiscountRequest;
use App\Models\Discount;
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
 * Class DiscountController
 * @package App\Http\Controllers\Backend
 */
class DiscountController extends BackendController
{
    /**
     * @var string
     */
    public $module = "discount";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'discount.read',
        'create'          => 'discount.create',
        'store'           => 'discount.create',
        'show'            => 'discount.read',
        'edit'            => 'discount.read',
        'update'          => 'discount.write',
        'destroy'         => 'discount.delete',
        'ajaxFieldChange' => 'discount.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.discounts'));

        $this->breadcrumbs(trans('labels.discounts'), route('admin.discount.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /discount
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Discount::select(
                'id',
                'price',
                'discount'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'discounts.id', '=', '$1')
                ->filterColumn('price', 'where', 'price', 'LIKE', '%$1%')
                ->filterColumn('discount', 'where', 'discount', '=', '$1')
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

        $this->data('page_title', trans('labels.discounts'));
        $this->breadcrumbs(trans('labels.discounts_list'));

        return $this->render('views.discount.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /discount/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Discount);

        $this->data('page_title', trans('labels.discount_create'));

        $this->breadcrumbs(trans('labels.discount_create'));

        return $this->render('views.discount.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /discount
     *
     * @param DiscountRequest $request
     *
     * @return \Response
     */
    public function store(DiscountRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Discount($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.discount.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /discount/{id}
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
     * GET /discount/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Discount::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.discount.index');
        }

        $this->data('page_title', '"'.$model->price.'(' . $model->discount . ')"');

        $this->breadcrumbs(trans('labels.discount_editing'));

        return $this->render('views.discount.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /discount/{id}
     *
     * @param  int              $id
     * @param DiscountRequest $request
     *
     * @return \Response
     */
    public function update($id, DiscountRequest $request)
    {
        try {
            $model = Discount::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.discount.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.discount.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /discount/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {

            $model = Discount::findOrFail($id);

            if (!$model->delete()) {

                FlashMessages::add("error", trans("messages.destroy_error"));

            } else {

                FlashMessages::add('success', trans("messages.destroy_ok"));

            }
        } catch (ModelNotFoundException $e) {

            FlashMessages::add('error', trans('messages.record_not_found'));

        }

        return Redirect::route('admin.discount.index');
    }
}