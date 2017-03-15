<?php

namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\UserRegister;
use App\Http\Requests\Frontend\Order\FastOrder;
use App\Http\Requests\Frontend\Order\OrderStore;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use App\Services\AuthService;
use App\Services\PageService;
use App\Services\UserService;
use Kingpabel\Shoppingcart\Facades\Cart;
use Sentry;
use Event;
use App\Events\Frontend\FastOrderStored;
use FlashMessages;
use Carbon\Carbon;
use App\Models\Page;

class OrderController extends FrontendController
{

    protected $pageService;

    protected $authService;

    protected $userService;

    private $page;

    public $module = 'order';

    function __construct(PageService $pageService, AuthService $authService, UserService $userService)
    {
        parent::__construct();

        $this->pageService = $pageService;

        $this->authService = $authService;

        $this->userService = $userService;
    }

    private function _init($slug) {

        $model = Page::with(['translations', 'parent', 'parent.translations'])->visible()->whereSlug($slug)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $this->page = $model;

    }

    public function getFast() {

        $this->_init('order-fast');

        return $this->render($this->pageService->getPageTemplate($this->page));

    }

    public function makeFast(FastOrder $request) {

        $data = $request->all();

        try {

            $user = Sentry::findUserByLogin($data['email']);

            if (is_null($user)) {

                $password = str_random(8);

                $input = [
                    'name' => '',
                    'email' => $data['email'],
                    'password' => $password,
                    'phone' => $data['phone']
                ];

                $user = $this->authService->register($input);

                $user->addGroup(Sentry::findGroupByName('Clients'));

                $this->userService->processUserInfo($user, $input);

                $this->userService->processFields($user);

                Event::fire(new UserRegister($user, $input));

            }

            Sentry::login($user, false);

            $order = Order::make([
                'address_id' => null,
                'date' => null,
                'time' => null,
                'prepay' => 50,
                'card_text' => '',
                'desc' => 'Быстрый заказ'
            ], $user);

            Event::fire(new FastOrderStored($order));

            FlashMessages::add('success',
                'Ваш заказ #' . $order->id . ' ожидает подтверждения оператора.
            Вам перезвонят для уточнения через несколько минут.');

            Cart::destroy();

            session()->forget('cart_discount_code');

            return redirect()->to(route('profile.orders'));

        } catch (\Exception $e) {
            FlashMessages::add('error', 'Произошла ошибка, попробуйте пожалуйста позже');

            return redirect()->back()->withInput($data);
        }

    }

    public function create() {

        $this->_init('order-make');

        $this->data('times', array(
            '1' => 'с 10:00 до 13:00',
            '2' => 'с 13:00 до 16:00',
            '3' => 'с 16:00 до 19:00',
            '4' => 'с 19:00 до 22:00',
            '5' => 'с 22:00 до 10:00'
        ));

        $user = Sentry::getUser();

        if($user) {
            $addresses = $user->addresses;
        } else {
            $addresses = array();
        }

        $this->data('addresses', $addresses);

        return $this->render($this->pageService->getPageTemplate($this->page));

    }

    public function store(OrderStore $request) {

        $data = $request->all();

        try {

            $selectedDate = strtotime($data['date']);

            $currentDate = strtotime(date("Y-m-d"));

            if ($selectedDate < $currentDate) {

                FlashMessages::add('error', 'Выбрана некорректная дата доставки');

                return redirect()->back()->withInput($data);

            }

            $data['date'] = Carbon::createFromFormat('Y-m-d', $data['date'])->format('d-m-Y');

            $user = Sentry::getUser();

            if ($data['address_id'] == 0) {
                $address = new Address();
                $address_string = trim(implode(', ', $request->only(['city', 'street', 'house', 'flat'])), ',');
                $address->fill([
                'address' => $address_string,
                    'code' => $data['code']
                ]);
                $user->addresses()->save($address);
                $data['address_id'] = $address->id;
            }

            $order = Order::make($data, $user);

            if (session()->has('cart_discount_code')) $user->addCode(session('cart_discount_code'));

            Cart::destroy();

            session()->forget('cart_discount_code');

            Event::fire(new FastOrderStored($order));

            FlashMessages::add('success',
                'Ваш заказ #' . $order->id . ' ожидает подтверждения оператора.
                Вам перезвонят для уточнения через несколько минут.');

            return redirect()->to(route('profile.orders'));
        
        } catch(\Exception $e) {
            FlashMessages::add('error', 'Произошла ошибка, попробуйте пожалуйста позже');

            return redirect()->back()->withInput($data);
        }
    }

    public function subscribe($id) {

        $user = Sentry::getUser();

        if ($user->hasSubscription($id)) {

            FlashMessages::add('error', 'Вы уже подписаны на эту подписку');


        } else {

            $user->subscriptions()->sync([
                $id => ['paid_before' => Carbon::now()->subDay()->toDateString()]
            ]);

            FlashMessages::add('success', 'Спасибо за подписку. Теперь Вы можете оплатить подписку в разделе "Подписки" личного кабинета.');

        }

        return redirect()->to(route('profile.subscriptions'));

    }

    public function makeSubscription($id) {
        $user = Sentry::getUser();

        $subscription = $user->subscriptions()->find($id);

        $order = new Order();

        $order->fill([
            'user_id' => $user->id,
            'address_id' => null,
            'courier_id' => null,
            'delivery_price' => 0,
            'prepay' => 100,
            'date' => Carbon::now()->format('d-m-Y'),
            'time' => null,
            'desc' => 'Оплата подписки',
            'card_text' => '',
            'status' => 2,
            'discount' => 0
        ]);

        $order->save();

        $orderItem = new OrderItem([
            'itemable_id' => $subscription->id,
            'price' => $subscription->price,
            'count' => 1,
            'itemable_type' => (string)Subscription::class
        ]);

        $order->items()->save($orderItem);

        return redirect()->to(route('order.pay', ['id' => $order->id]));
    }

    public function show($id) {

        $this->_init('order-pay');

        $order = Order::with('items', 'items.itemable')->find($id);

        $this->data('order', $order);

        return $this->render($this->pageService->getPageTemplate($this->page));
    }

    public function destroy($id) {

        $order = Order::with('items')->find($id);

        abort_if(!$order, 404);

/*        try {

            $itemable = $order->items()->first();

            if(isset($itemable) && $itemable->itemable_type == (string)Subscription::class) {

                $user = Sentry::getUser();

                $user->subscriptions()->detach($itemable->itemable_id);

            }


        } catch (\Exception $e) {}*/

        $order->status = 0;

        $order->save();

        FlashMessages::add('success', 'Заказ отменён');

        return redirect()->to(route('profile.orders'));
    }

    public function success() {

        $this->_init('order-success');

        return $this->render($this->pageService->getPageTemplate($this->page));
    }

    public function fail() {
        $this->_init('order-fail');

        return $this->render($this->pageService->getPageTemplate($this->page));
    }

}
