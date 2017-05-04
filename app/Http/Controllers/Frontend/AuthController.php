<?php namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\UserRegister;
use App\Http\Requests\Frontend\User\PasswordChange;
use App\Models\Discount;
use App\Services\AuthService;
use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
use App\Models\User;
use App\Services\MessageService;
use App\Services\PageService;
use App\Services\UserService;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use DB;
use Event;
use Exception;
use FlashMessages;
use Kingpabel\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Mail;
use Sentry;
use App\Models\Page;

/**
 * Class AuthController
 * @package App\Http\Controllers\Frontend
 */
class AuthController extends FrontendController
{

    public $module = 'profile';
    
    /**
     * @var \App\Services\AuthService
     */
    protected $authService;

    /**
     * @var \App\Services\UserService
     */
    protected $userService;

    protected $pageService;

    protected $page = null;
    
    /**
     * AuthController constructor.
     *
     * @param \App\Services\AuthService $authService
     * @param \App\Services\UserService $userService
     */
    public function __construct(AuthService $authService, UserService $userService, PageService $pageService)
    {
        parent::__construct();
        
        $this->authService = $authService;
        $this->userService = $userService;
        $this->pageService = $pageService;

    }

    private function _init($slug = null) {

        if($slug) {

            $type = $slug;

        } else {

            $type = explode('/', request()->path());

            $type = array_pop($type);

        }

        $model = Page::with(['translations', 'parent', 'parent.translations'])->visible()->whereSlug($type)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $this->page = $model;

    }
    
    /**
     * @return mixed
     */
    public function getLogin()
    {

        $this->_init();

        return $this->render($this->pageService->getPageTemplate($this->page));
    }
    
    /**
     * @param array $credentials
     *
     * @return mixed
     */
    public function postLogin($credentials = [])
    {
        $credentials = !empty($credentials) ? $credentials : [
            'login'    => request('login'),
            'password' => request('password'),
        ];
        
        try {
            if ($user = $this->authService->login($credentials)) {

                $price = $user->getTotalOrdersPrice();

                $discount = Discount::where('price', '<=', $price)->orderBy('price', 'DESC')->first();

                if(isset($discount) && $discount->discount > $user->discount) {

                    $user->discount = $discount->discount;

                    $user->save();

                }

                if (sizeof(Cart::count())) {
                    foreach (Cart::content() as $row) {
                        if ($row->options['category'] != 'sales')
                            Cart::update($row->rowid, ['discount' => CartController::_itemDiscount($row->price)]);
                    }
                }

                return redirect()->route('profile.orders');
            }
        }
        catch (UserNotFoundException $e) {
            FlashMessages::add('error', 'Пользователя с таким Email не существует');
        }
        catch (WrongPasswordException $e) {
            FlashMessages::add('error', 'Неверный логин или пароль');
        }
        catch (Exception $e) {
            FlashMessages::add('error', 'Произошла ошибка, попробуйте пожалуйста позже');
        }
        return redirect()->back()->withInput($credentials);

    }
    
    /**
     * @return mixed
     */
    public function getLogout()
    {
        Sentry::logout();
        
        FlashMessages::add('notice', trans('messages.you have successfully logout'));
        
        return redirect()->home();
    }
    
    /**
     * @return array
     */
    public function getRegister()
    {

        $this->_init();

        return $this->render($this->pageService->getPageTemplate($this->page));
    }
    
    /**
     * @param \App\Http\Requests\Frontend\Auth\UserRegisterRequest $request
     * @param \App\Services\AuthService                            $authService
     *
     * @return mixed
     */
    public function postRegister(UserRegisterRequest $request, AuthService $authService)
    {
        $input = $request->all();
        
        DB::beginTransaction();
        
        try {

            $input = $this->authService->prepareRegisterInput($request);
            
            $user = $authService->register($input);

            $this->userService->processUserInfo($user, $input);

            $this->userService->processFields($user);

            $user->addGroup(Sentry::findGroupByName('Clients'));
            
            DB::commit();

            Event::fire(new UserRegister($user, $input));

            $this->authService->login(['login' => $user->login, 'password' => $input['password']]);

            session()->flash('register_status', 'success');

            return redirect()->route('home');

        } catch (UserExistsException $e) {
            $message = 'Пользователь с таким логином уже существует';
        }
        catch (Exception $e) {
            $message = 'Произошла ошибка, попробуйте пожалуйста позже';
        }

        DB::rollBack();
        
        FlashMessages::add('error', $message);
        
        return redirect()->back()->withInput($input);
    }


    /**
     * @return array
     */
    public function getReset()
    {

        $this->_init();

        return $this->render($this->pageService->getPageTemplate($this->page));
    }
    
    /**
     * @param Request $request
     *
     * @return $this
     */
    public function postReset(Request $request)
    {
        $login = $request->get('login');
        
        try {

            $user = Sentry::findUserByLogin($login);

            if($user->login == $user->email) {

                Mail::queue(
                    'emails.password',
                    ['login' => $login, 'token' => $user->getResetPasswordCode()],
                    function ($message) use ($user) {
                        $message->to($user->login, $user->getFullName())
                            ->subject('Восстановление пароля');
                    }
                );

                FlashMessages::add('success', 'Инструкции по восстановлению отправлены Вам на Email');

            } elseif($user->login == $user->phone) {

                $code = random_int(1000, 9999);

                $user->reset_password_code = $code;

                $user->save();

                $messageService = new MessageService();

                $messageService->passwordResetSMS($user, $code);

                session()->flash('password_reset_status', 'success');
                session()->flash('reset_password_code', $code);
                session()->flash('reset_password_login', $login);

                return redirect()->back();

            }

        }
        catch (UserNotFoundException $e) {

            FlashMessages::add('error', 'Пользователя с таким логином не существует');

        }
        catch (Exception $e) {

            FlashMessages::add('error', 'Произошла ошибка, попробуйте пожалуйста позже');

        };

        return redirect()->back();
    }

    public function postResetPhone(Request $request) {

        $digits = $request->get('code', []);

        $token = implode("", $digits);

        $login = $request->get('login');

        try {
            $user = Sentry::findUserByLogin($login);

            if ($user->checkResetPasswordCode($token)) {

                return ['status' => 'success', 'redirect' => route('password.token', ['login' => $login, 'token' => $token])];

            }
        }  catch (Exception $e) {}

        return ['status' => 'error'];

    }
    
    /**
     * @param string $login
     * @param string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getRestore($login = '', $token = '')
    {

        $error = null;

        try {
            $user = Sentry::findUserByLogin($login);

            if ($user->checkResetPasswordCode($token)) {

                $this->_init('password-reset-with-token');

                $this->data('token', $token);

                $this->data('login', $login);

                return $this->render($this->pageService->getPageTemplate($this->page));

            }
        } catch (UserNotFoundException $e) {
            $error = 'Пользователя с таким логином не существует';
        } catch (Exception $e) {
            $error = 'Произошла ошибка, попробуйте еще раз';
        }

        if($error) {

            FlashMessages::add('error', $error);

            return redirect()->back();

        }

        abort(404);
        
    }

    public function postRestore($login = '', $token = '', PasswordChange $request) {

        $error = null;

        try {
            $user = Sentry::findUserByLogin($login);

            if ($user->checkResetPasswordCode($token)) {

                $this->userService->updatePassword($user, $request->get('password'));

                FlashMessages::add('success', 'Пароль успешно изменен');

                return redirect()->back();

            }
        } catch (UserNotFoundException $e) {

            $error = 'Пользователя с таким логином не существует';

        } catch (Exception $e) {

            $error = 'Произошла ошибка, попробуйте пожалуйста позже';

        }

        if($error) {

            FlashMessages::add('error', $error);

            return redirect()->back();

        }

        abort(404);

    }


    /**
     * Socialite auth callback method
     *
     * @param null $provider
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function socialiteCallback($provider = null) {
        $user = Socialite::with($provider)->user();

        if ($user) {
            switch ($provider) {
                case "vkontakte":
                    $user = $this->authWithVK($user);
                    break;
                case "google":
                    $user = $this->authWithGoogle($user);
                    break;
                case "facebook":
                    $user = $this->authWithFacebook($user);
                    break;
                case "instagram":
                    $user = $this->authWithInstagram($user);
                    break;
                default:
                    abort(404);
            }

            if (is_array($user)) return redirect(route('reg', $user));

            Sentry::login($user, true);

            $price = $user->getTotalOrdersPrice();

            $discount = Discount::where('price', '<=', $price)->orderBy('price', 'DESC')->first();

            if(isset($discount) && $discount->discount > $user->discount) {

                $user->discount = $discount->discount;

                $user->save();

            }

            if (Cart::count() != 0) {
                foreach (Cart::content() as $row) {
                    if ($row->options['category'] != 'sales')
                        Cart::update($row->rowid, ['discount' => $user->getDiscount()]);
                }
            }

            return redirect()->route('profile.orders');
        }

        FlashMessages::add('error', 'Ошибка авторизации');

        return redirect()->route('reg');
    }

    /**
     * Authorize with Vkontakte via Socialite
     *
     * @param $user
     * @return array
     */
    private function authWithVK($user) {
        $user = $user->user;
        $user_exists = User::with('info')->where('email', $user['email'])->first();

        if (is_null($user_exists)) return [
            'name' => $user['first_name'].' '.$user['last_name'],
            'email' => $user['email']
        ];

        return $user_exists;
    }

    /**
     * Authorize with Google+ via Socialite
     *
     * @param $user
     * @return array
     */
    private function authWithGoogle($user) {
        $email = $user->email;
        $name = $user->user['name']['givenName'].' '.$user->user['name']['familyName'];

        $user_exists = User::with('info')->where('email', $email)->first();

        if (is_null($user_exists)) return [
            'name' => $name,
            'email' => $email
        ];

        return $user_exists;
    }

    /**
     * Authorize with Facebook via Socialite
     *
     * @param $user
     * @return array
     */
    private function authWithFacebook($user) {
        $email = $user->email;
        $name = $user->name;

        $user_exists = User::with('info')->where('email', $email)->first();

        if (is_null($user_exists)) return [
            'name' => $name,
            'email' => $email
        ];

        return $user_exists;
    }

    /**
     * Authorize with Instagram via Socialite
     *
     * @param $user
     * @return array
     */
    private function authWithInstagram($user) {
        $email = $user->email;
        $name = $user->name;

        $user_exists = User::with('info')->where('email', $email)->first();

        if (is_null($user_exists)) return [
            'name' => $name,
            'email' => $email
        ];

        return $user_exists;
    }


    /**
     * Socialite auth redirect method
     *
     * @param null $provider
     * @return mixed
     */
    public function socialiteRedirect($provider = null) {

        if (!config('services.'.$provider)) abort(400);

        return Socialite::with($provider)->redirect();
    }

    public function logout() {

        Sentry::logout();

        return redirect()->back();
    }
}