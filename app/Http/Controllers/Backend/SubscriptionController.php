<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Subscription\SubscriptionRequest;
use App\Models\Subscription;
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
 * Class SubscriptionController
 * @package App\Http\Controllers\Backend
 */
class SubscriptionController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "subscription";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'subscription.read',
        'create'          => 'subscription.create',
        'store'           => 'subscription.create',
        'show'            => 'subscription.read',
        'edit'            => 'subscription.read',
        'update'          => 'subscription.write',
        'destroy'         => 'subscription.delete',
        'ajaxFieldChange' => 'subscription.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.subscriptions'));

        $this->breadcrumbs(trans('labels.subscriptions'), route('admin.subscription.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /subscription
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Subscription::joinTranslations('subscriptions')->select(
                'subscriptions.id',
                'subscription_translations.title',
                'subscriptions.price',
                'subscriptions.status',
                'subscriptions.position'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'subscriptions.id', '=', '$1')
                ->filterColumn('subscriptions.price', 'where', 'subscriptions.price', 'LIKE', '%$1%')
                ->filterColumn('subscription_translations.title', 'where', 'subscription_translations.title', 'LIKE', '%$1%')
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
                ->removeColumn('content')
                ->make();
        }

        $this->data('page_title', trans('labels.subscriptions'));
        $this->breadcrumbs(trans('labels.subscriptions_list'));

        return $this->render('views.subscription.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /subscription/create
     *
     * @return Response
     */
    public function create()
    {

        $this->data('model', new Subscription);

        $this->data('page_title', trans('labels.subscription_create'));

        $this->breadcrumbs(trans('labels.subscription_create'));

        return $this->render('views.subscription.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /subscription
     *
     * @param SubscriptionRequest $request
     *
     * @return \Response
     */
    public function store(SubscriptionRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {
            $model = new Subscription($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.subscription.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /subscription/{id}
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
     * GET /subscription/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Subscription::with('translations')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.subscription.index');
        }

        $this->data('page_title', '"'.$model->title.'"');

        $this->breadcrumbs(trans('labels.subscription_editing'));

        return $this->render('views.subscription.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /subscription/{id}
     *
     * @param  int              $id
     * @param SubscriptionUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, SubscriptionRequest $request)
    {
        try {
            $model = Subscription::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.subscription.index');
        }

        $input = $request->all();

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.subscription.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /subscription/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {

            $model = Subscription::findOrFail($id);

            if (!$model->delete()) {

                FlashMessages::add("error", trans("messages.destroy_error"));

            } else {

                FlashMessages::add('success', trans("messages.destroy_ok"));

            }
        } catch (ModelNotFoundException $e) {

            FlashMessages::add('error', trans('messages.record_not_found'));

        }

        return Redirect::route('admin.subscription.index');
    }
}