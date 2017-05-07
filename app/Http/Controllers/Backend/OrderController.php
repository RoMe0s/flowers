<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Order\OrderCreateRequest;
use App\Http\Requests\Backend\Order\OrderRequest;
use App\Models\Individual;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserInfo;
use App\Services\MessageService;
use Carbon\Carbon;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Meta;
use Redirect;
use Response;
use App\Models\Address;
use Event;
use App\Events\Backend\OrderStatusChanged;

/**
 * Class OrderController
 * @package App\Http\Controllers\Backend
 */
class OrderController extends BackendController
{
    public $statuses = [];

    /**
     * @var string
     */
    public $module = "order";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'order.read',
        'create'          => 'order.create',
        'show'            => 'order.read',
        'store'           => 'order.create',
        'edit'            => 'order.write',
        'update'          => 'order.write',
        'ajaxFieldChange' => 'order.write',
        'pay_status'      => 'order.paystatus'
    ];

    /**
     * @param \Illuminate\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        $this->breadcrumbs(trans('labels.orders'), route('admin.order.index'));

        Meta::title(trans('labels.order'));

        $this->statuses = Order::getStatuses();
    }

    /**
     * Display a listing of the resource.
     * GET /order
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {

            $list = Order::select(
                'id',
                'recipient_name',
                'delivery_price',
                'prepay',
                'date',
                'created_at',
                'status'
            );

            if($request->get('status') !== null && $request->get('status') !== "") {
                $list->where('status', $request->get('status'));
            }

            return $dataTables = Datatables::of($list)
                ->editColumn(
                    'date',
                    function ($model) {
                        if($model->date) {
                            return Carbon::createFromFormat('d-m-Y', $model->date)->format('Y-m-d');
                        }
                        return trans('labels.no');
                    }
                )
                ->editColumn(
                    'delivery_price',
                    function ($model) {
                        return $model->getTotal() . ' руб.';
                    }
                )
                ->editColumn(
                    'prepay',
                    function ($model) {
                        return $model->totalPrepay() . ' руб.(' . $model->prepay . '%)';
                    }
                )
                ->editColumn(
                    'status',
                    function ($model) {
                        return view(
                            'order.partials.status_switcher',
                            ['model' => $model, 'statuses' => $this->statuses]
                        )->render();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => 'order', 'delete' => false]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->make();
        }

        $this->data('page_title', trans('labels.orders'));

        $this->breadcrumbs(trans('labels.orders_list'));

        $this->data('statuses', $this->statuses);

        return $this->render('views.order.index');

    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function show($id)
    {
        $model = Order::with('items')->findOrFail($id);

        $this->data('model', $model);

        $this->data('page_title', '"'.$model->recipient_name . ' ' . $model->created_at . '"');

        $this->breadcrumbs($model->id);

        $this->data('times', Order::getTimes());

        return $this->render('views.order.show');
    }

    /**
     * @return $this
     */
    public function create()
    {
        $this->data('model', new Order);

        $this->data('page_title', trans('labels.order_create'));

        $basket_items = session()->get('basket_items', []);

        $this->data('items', $basket_items);

        $this->data('discount', 0);

        $this->breadcrumbs(trans('labels.order_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('order.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /order
     *
     * @param \App\Http\Requests\Backend\Order\OrderCreateRequest $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(OrderCreateRequest $request)
    {

        DB::beginTransaction();

        $messageService = new MessageService();

        try {
            $input = $request->all();

            $user_id = $request->get('user_id', null);

            $user_info = $request->get('user', []);

            if(!$user_id || sizeof($user_info)) {

                $user = new User();

                $user_info['login'] = $user_info['phone'];

                $user_info['password'] = random_int(10000, 99999);

                $user_info['activated'] = true;

                $user->fill($user_info);

                $user->save();

                $user_id = $user->id;

                $messageService->registerSMS($user, $user_info['password']);

            } else {

                $user = User::find($user_id);

            }

            if(!isset($input['address_id'])) {

                $input['address_id'] = $this->_proccessAddress($user_id);

            }

            $model = new Order($input);

            $model->save();

            $items = $request->get('items', []);

            if(isset($input['individual']) && sizeof($input['individual'])) {

                $individual = new Individual();

                $input['individual']['email'] = $user->email;

                $individual->fill($input['individual']);

                $individual->save();

                $item = [
                    'itemable_type' => "App\\Models\\" . class_basename($individual),
                    'itemable_id' => $individual->id,
                    'price' => $individual->price,
                    'count' => 1
                ];

                $items[] = $item;

            }

            foreach ($items as $item) {

                $item = new OrderItem($item);

                $model->items()->save($item);
            }

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            session()->forget('basket_items');

            $messageService->orderStoredSMS($model);

            return Redirect::route('admin.order.index');

        } catch (Exception $e) {

            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Order::with('items')->findOrFail($id);

            $this->_fillAdditionalTemplateData($model);

            $this->data('page_title', '"'.$model->recipient_name . ' ' . $model->created_at . '"');

            $this->breadcrumbs(trans('labels.order_editing'));

            $this->data('discount', $model->discount);

            return $this->render('views.order.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.order.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                        $id
     * @param \App\Http\Requests\Backend\Order\OrderRequest $request
     *
     * @return \Response
     */
    public function update($id, OrderRequest $request)
    {

        try {

            $input = $request->all();

            $model = Order::findOrFail($id);

            $change = $this->pay_status($model, $input);

            if($change !== TRUE) {

                FlashMessages::add('error', $change);

            }

            $input['user_id'] = isset($input['user_id']) && $input['user_id'] != "" ? $input['user_id'] : $this->_processUser();

            if(!isset($input['address_id'])) {
                $input['address_id'] = $this->_proccessAddress($input['user_id']);
            }

            DB::beginTransaction();

            $model->fill($input);

            $model->save();

            $this->_processItems($model);

            DB::commit();

            if($model->status ==  2) {
                Event::fire(new OrderStatusChanged($model));
            }

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.order.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.order.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Order::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.order.index');
    }

    /**
     * fill additional template data
     */
    private function _fillAdditionalTemplateData($model = null)
    {
        $group = \Sentry::findGroupByName('Couriers');
        $couriers = User::select([
            'users.id',
            \DB::raw('CONCAT(user_info.name,",",user_info.phone) as pn')
        ])
            ->leftJoin('user_info','users.id', '=', 'user_info.user_id')
            ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->where('users_groups.group_id', $group->id)
            ->lists('pn', 'id')->toArray();
        $this->data('couriers', $couriers);

        $this->data('addresses', Address::all());

        $this->data('times', Order::getTimes());

        $this->data('prepay', array(
            '50' => '50%',
            '100' => '100%'
        ));

        $this->data('statuses', $this->statuses);

        $users = User::select([
            'users.id',
            'user_info.phone',
            'user_info.name',
            'users.email',
            'users.discount'
        ])->leftJoin('user_info','users.id', '=', 'user_info.user_id');

        if($model) {

            $tmp_baskets = session()->get('basket_items', []);
            $baskets = '';

            foreach ($tmp_baskets as $tmp_basket)
                foreach ($tmp_basket as $item)
                    $baskets .= "<option
                value='{$model->id}'
                data-count='{$item->basket_count}'
                data-itemable_id='{$item->id}'
                data-itemable_type='" . class_basename($item) . "'
                data-price='{$item->price}'
                >{$item->name}</option>";


            $this->data('baskets', $baskets);

//            $users = $users->where('users.id', $model->user_id);

        }

         $users = $users->get();

        $this->data('users', $users);

    }

    private function _processUser() {
            $user_info['email'] = request('email');
            $user_info['password'] = request('password');
            $user_info['name'] = request('recipient_name');
            $user_info['phone'] = request('recipient_phone');
            $user_info['start_discount'] = 0;
            $user_info['notifications'] = true;
            $user_info['activated'] = true;
            try
            {
                $user = new User();
                $user->fill($user_info);
                $user->activated = true;
                $user->save();
                $userinfo = new UserInfo();
                $userinfo->fill($user_info);
                $user->info()->save($userinfo);
                $group = \Sentry::findGroupByName('Clients');
                $user->groups()->attach($group->id);
                return $user->id;
            }
            catch (Exception $e)
            {
                return null;
            }
    }

    /**
     * @param \App\Models\Order $model
     */
    private function _processItems(Order $model)
    {
        $data = request('items.remove', []);
        foreach ($data as $id) {
            try {
                $item = $model->items()->findOrFail($id);
                $item->delete();
            } catch (Exception $e) {
                FlashMessages::add("error", trans("messages.item destroy failure"." ".$id.". ".$e->getMessage()));
                continue;
            }
        }

        $data = request('items.old', []);
        foreach ($data as $key => $item) {
            try {
                $_item = OrderItem::findOrFail($key);
                $_item->update($item);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.item update failure"." ".$e->getMessage())
                );
                continue;
            }
        }

        $data = request('items.new', []);
        foreach ($data as $item) {
            try {
                $item = new OrderItem($item);
                $model->items()->save($item);
            } catch (Exception $e) {
                FlashMessages::add(
                    "error",
                    trans("messages.item save failure"." ".$e->getMessage())
                );
                continue;
            }
        }
    }

    private function _proccessAddress($user_id) {
        $input['address'] = request('address');
        $input['code'] = request('code');
        $input['user_id'] = $user_id;
        $address = new Address();
        $address->fill($input);
        $address->save();
        return $address->id;
    }

    //BASKET

    public function addBasketItemToOrder(Request $request) {
        try {
            $input = $request->except('id');
            $input['itemable_type'] = "App\\Models\\" . $input['itemable_type'];
            $model = Order::find($request->get('id'));
            $already_item = OrderItem::where('itemable_id', $input['itemable_id'])
                ->where('itemable_type', $input['itemable_type'])
                ->where('order_id', $request->get('id'))
                ->first();
            if($already_item) {
                $already_item->update([
                    'price' => $input['price'],
                    'count' => $already_item->count + $input['count']
                ]);
                $item = $already_item;
            } else {
                $item = new OrderItem();
                $item->fill($input);
                $model->items()->save($item);
            }

            $html = view('order.partials.item')->with(['item' => $item, 'discount' => $model->discount, 'model' => $model])->render();

            $basket_items = session()->get('basket_items', []);

            unset($basket_items[strtolower($request->get('itemable_type'))][$request->get('itemable_id')]);

            $count = 0;
            foreach($basket_items as $par_items)
                $count += count($par_items);
            if($count > 0) {
                session()->put('basket_items', $basket_items);
                session()->pub('basket_count', $count);
            }
            else {
                session()->forget('basket_items');
                session()->forget('basket_count');
            }

            return ['status' => 'success', 'message' => trans('messages.save_ok'), 'html' => $html, 'count' => $count, 'update' => isset($already_item) ? $item->id : false];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => trans('messages.an error has occurred, try_later')];
        }
    }

    public function basket() {

        $this->data('page_title', trans('labels.basket'));

        $class_prefix = "App\\Models\\";

        $types = [
            $class_prefix . 'Set' => trans('labels.sets'),
            $class_prefix . 'Bouquet' => trans('labels.bouquets'),
            $class_prefix . 'Individual' => trans('labels.individuals'),
            $class_prefix . 'Product' => trans('labels.products'),
            $class_prefix . 'Sale' => trans('labels.sales')
        ];

        $this->data('types', $types);

        $this->data('orders', Order::lists('id', 'id')->toArray());

        $this->breadcrumbs(trans('labels.basket'));

        $basket_items = session()->get('basket_items', []);

        $this->data('items', $basket_items);

        $this->data('discount', 0);

        return $this->render('views.order.basket');
    }

    public function add(Request $request) {
        try {
            $class = "App\\Models\\" . $request->get('type');
            $item = $class::find($request->get('id'));
            $already_items = session()->get('basket_items', []);

            if(isset($already_items[strtolower($request->get('type'))][$request->get('id')])) {
                $already_items[strtolower($request->get('type'))][$request->get('id')]->basket_count = $already_items[strtolower($request->get('type'))][$request->get('id')]->basket_count+1;
                $message = trans('messages.item count added');
            } else {
                $item->basket_count = 1;
                $already_items[strtolower($request->get('type'))][$request->get('id')] = $item;
                $message = trans('messages.add to basket ok');
            }
            session()->put('basket_items', $already_items);
            $count = 0;
            foreach($already_items as $par_items)
                $count += count($par_items);
            session()->put('basket_count', $count);
            return ['status' => 'success', 'message' => $message, 'count' => $count];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => trans('messages.an error has occurred, try_later')];
        }
    }

    public function remove(Request $request) {
        $already_items = session()->get('basket_items', []);
        unset($already_items[strtolower($request->get('type'))][$request->get('id')]);
        $count = 0;
        foreach($already_items as $par_items)
            $count += count($par_items);
        if($count > 0) {
            session()->put('basket_items', $already_items);
            session()->put('basket_count', $count);
        }
        else {
            session()->forget('basket_count');
            session()->forget('basket_items');
            $already_items = array();
        }
        return ['status' => 'success', 'message' => trans('messages.save_ok'), 'count' => $count, 'html' => $this->_renderBasket($already_items)];
    }

    private function _renderBasket($items = array()) {
        return view('views.order.tabs.basket_items', compact('items'))->render();
    }

    public function addToOrder(Request $request) {
        $model = Order::with('items')->where('id', $request->get('id'))->first();
        if(!$model) {
            return ['status' => 'error', 'message' => trans('messages.this order is not exist')];
        }

        try {
            $basket_items = session()->get('basket_items', []);
            foreach ($basket_items as $type => $basket_item) {
                foreach ($basket_item as $item) {
                    $isset_item = $model->items()->where('itemable_id', $item->id)->where('itemable_type', "App\\Models\\" . class_basename($item))->first();
                    if ($isset_item) {

                        $isset_item->update([
                            'price' => $item->price,
                            'count' => $isset_item->count + $item->basket_count
                        ]);

                    } else {

                        $fillable = array(
                            'itemable_id' => $item->id,
                            'itemable_type' => "App\\Models\\" . class_basename($item),
                            'price' => $item->price,
                            'count' => $item->basket_count
                        );

                        $oi = new OrderItem();
                        $oi->fill($fillable);
                        $model->items()->save($oi);

                    }
                }
            }
            session()->forget('basket_items');
            return ['status' => 'success', 'message' => trans('messages.save_ok'), 'html' => $this->_renderBasket([])];
        } catch (Exception $e) {

        }

        return ['status' => 'error', 'message' => trans('messages.an error has occurred, try_later')];

    }

    public function changeStatus(Request $request) {
        DB::beginTransaction();
        try {

            $input = $request->all();

            $model = Order::where('id', $request->get('id'))->first();

            $change = $this->pay_status($model, $input);

            if($change !== TRUE) {

                return ['status' => 'warning', 'message' => $change];

            }

            DB::commit();

            if($model->status ==  2) {

                Event::fire(new OrderStatusChanged($model));

            }

            return ['status' => 'success', 'message' => trans('messages.save_ok')];

        } catch (Exception $e) {

            DB::rollBack();

            return ['status' => 'success', 'message' => trans('messages.an error has occurred, try_later')];

        }
    }

    public function reloadItems(Request $request) {

        $discount = $request->get('discount');

        if(!$request->get('id')) {

            $items = session()->get('basket_items', []);

            $html = view('order.tabs.basket_items')->with(['items' => $items, 'discount' => $discount])->render();

        } else {

            $model = Order::with('items')->find($request->get('id'));

            $html = view('order.tabs.items')->with(['model' => $model, 'discount' => $discount])->render();

        }

        return ['html' => $html];

    }

    public function load_users($id) {

        $model = Order::find($id);

        $users = User::select([
            'users.id',
            'user_info.phone',
            'user_info.name',
            'users.email',
            'users.discount'
        ])
            ->leftJoin('user_info','users.id', '=', 'user_info.user_id')
            /*        ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                    ->where('users_groups.group_id', $group->id)*/
            ->get();

        return ['html' =>view('order.partials.users_list')->with(['model' => $model, 'users' => $users])->render()];

    }

    private function pay_status($model, &$input) {

        if(isset($input['status'])) {

            $old_status = $model->status;

            if(in_array($input['status'], [3,6]) || (in_array($old_status, [3,6]) && !in_array($input['status'], [4,5]))) {

                $user = \Sentry::getUser();

                if($user->hasAccess('order.paystatus')) {

                    $model->status = $input['status'];

                    $model->save();

                } else {

                    unset($input['status']);

                    return 'У вас нет прав на изменение статуса, статус не был изменён';

                }

            } else {

                $model->status = $input['status'];

                $model->save();
            }

        }

        return true;

    }

}