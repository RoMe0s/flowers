<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Requests\Frontend\User\UserPasswordUpdateRequest;
use App\Http\Requests\Frontend\User\UserUpdateRequest;
use App\Models\Discount;
use App\Models\Page;
use App\Models\Subscription;
use App\Models\UserInfo;
use App\Services\UserService;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Exception;
use FlashMessages;
use Sentry;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Frontend
 */
class ProfileController extends FrontendController
{
    
    /**
     * @var string
     */
    public $module = 'profile';
    
    /**
     * @var UserService
     */
    private $userService;

    private $currentUser;

    /**
     * ProfileController constructor.
     *
     * @param \App\Services\UserService    $userService
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();

        $this->userService = $userService;

        $model = Page::with(['translations', 'parent', 'parent.translations'])->visible()->whereSlug($this->module)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $titles = [
            'profile' => 'Профиль',
            'subscriptions' => 'Подписки',
            'discounts' => 'Накопительные скидки',
            'orders' => 'История заказов',
            'addresses' => 'Адреса доставки'
        ];

        try {
            $selected = explode('/', request()->path());
            $selected = $titles[array_pop($selected)];
            $model->name = $model->name . ' | ' . $selected;
            $model->meta_title = $model->name;
        } catch (Exception $e) {
            $selected = null;
        }

        $this->data('title', $selected);

        $this->fillMeta($model, $this->module);

        $this->currentUser = $this->_getUser();

        view()->share('user', $this->currentUser);
    }
    
    /**
     * @return $this
     */
    public function index()
    {
        return $this->render($this->module.'.index');
    }

    /**
     * @param \App\Http\Requests\Frontend\User\UserUpdateRequest $request
     *
     * @return mixed
     */
    public function update(UserUpdateRequest $request)
    {
        $model = $this->currentUser;

        try {
            $input = $this->userService->prepareInput($request);

            $this->userService->update($model, $input);

            FlashMessages::add('success', 'Изменения успешно сохранены');

            return redirect()->route('profile');
        } catch (Exception $e) {
            FlashMessages::add('error', 'Произошла ошибка, попробуйте пожалуйста позже');
        }

        return redirect()->route('profile');
    }

    public function subscriptions() {

        $subscriptions = $this->currentUser->subscriptions;

        $this->data('subscriptions', $subscriptions);

        return $this->render($this->module.'.subscriptions');

    }

    public function discounts() {

        $discounts = Discount::all()->toArray();

        $this->data('discounts', $discounts);

        return $this->render($this->module.'.discounts');
    }

    public function orders() {

        $orders = $this->currentUser->orders()->with(['items'])->whereHas('items', function($query) {
            return $query->where('itemable_type', '<>', (string)Subscription::class);
        })->orderBy('id', 'DESC')->get();

        $this->data('orders', $orders);

        return $this->render($this->module.'.orders');
    }

    public function addresses() {

        $addresses = $this->currentUser->addresses()->select('address')->orderBy('id', 'DESC')->get();

        $this->data('addresses', $addresses);

        return $this->render($this->module.'.addresses');
    }
    
    /**
     * get user by id or logout & abort if not find
     *
     * @param int|bool $id
     *
     * @return mixed
     */
    private function _getUser($id = false)
    {
        $user = $this->userService->getUserById($id ? : $this->user->id);

        if (!$user) {
            Sentry::logout();
            
            abort(404);
        }
        
        return $user;
    }
}