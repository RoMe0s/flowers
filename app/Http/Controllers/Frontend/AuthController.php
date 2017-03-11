<?php namespace App\Http\Controllers\Frontend;

use App\Events\Frontend\UserRegister;
use App\Exceptions\NotValidImageException;
use App\Services\AuthService;
use App\Http\Requests\Frontend\Auth\UserRegisterRequest;
use App\Models\User;
use App\Services\PageService;
use App\Services\UserService;
use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use DB;
use Event;
use Exception;
use FlashMessages;
use Illuminate\Http\Request;
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

    private function _init() {

        $type = explode('/', request()->path());

        $type = array_pop($type);

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
            'email'    => request('email'),
            'password' => request('password'),
        ];
        
        try {
            if ($user = $this->authService->login($credentials)) {
                return redirect()->to(route('profile'));
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
            $this->validateImage('image');
            
            $input = $this->authService->prepareRegisterInput($request);
            
            $user = $authService->register($input);

            $this->userService->processUserInfo($user, $input);

            $this->userService->processFields($user);
            
            Event::fire(new UserRegister($user, $input));
            
            DB::commit();
            
            FlashMessages::add(
                'success',
                trans('messages.user register success message')
            );
            
            return redirect()->back();
        } catch (NotValidImageException $e) {
            FlashMessages::add(
                'error',
                trans('messages.trying to load is too large file or not supported file extension')
            );
        } catch (Exception $e) {
            $message = trans('messages.user register error');
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
    public function postRestore(Request $request)
    {
        $email = $request->get('email');
        
        try {
            $user = Sentry::findUserByLogin($email);
            
            if ($user->activated) {
                Mail::queue(
                    'emails.auth.restore',
                    ['email' => $email, 'token' => $user->getResetPasswordCode()],
                    function ($message) use ($user) {
                        $message->to($user->email, $user->getFullName())
                            ->subject(trans('labels.password_restore_subject'));
                    }
                );
                
                return [
                    'status'  => 'success',
                    'message' => trans('messages.password restore message'),
                ];
            }
            
            $error = trans('messages.user with such email was not activated');
        } catch (UserNotFoundException $e) {
            $error = trans('messages.user with such email was not found');
        } catch (Exception $e) {
            $error = trans('messages.an error has occurred, try_later');
        };
        
        return [
            'status'  => 'error',
            'message' => $error,
        ];
    }
    
    /**
     * @param string $email
     * @param string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
/*    public function getReset($email = '', $token = '')
    {
        try {
            $user = Sentry::findUserByLogin($email);
            
            if ($user->checkResetPasswordCode($token)) {
                $password = str_random(6);
                
                if ($user->attemptResetPassword($token, $password)) {
                    Mail::queue(
                        'emails.auth.reset',
                        ['email' => $email, 'password' => $password],
                        function ($message) use ($user) {
                            $user = User::find($user->id);
                            
                            $message->to($user->email, $user->getFullName())
                                ->subject(trans('labels.password_reset_success_subject'));
                        }
                    );
                    
                    FlashMessages::add(
                        'success',
                        trans('messages.password restore success message')
                    );
                    
                    return redirect()->home();
                } else {
                    $error = trans('messages.you have entered an invalid code');
                }
            } else {
                $error = trans('messages.you have entered an invalid code');
            }
        } catch (UserNotFoundException $e) {
            $error = trans('messages.user with such email was not found');
        } catch (Exception $e) {
            $error = trans('messages.an error has occurred, try_later');
        }
        
        FlashMessages::add('error', $error);
        
        return redirect()->home();
    }*/
}