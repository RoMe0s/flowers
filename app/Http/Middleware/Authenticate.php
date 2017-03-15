<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Middleware;

use Closure;
use FlashMessages;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\ResponseFactory;
use Sentry;
use Session;
use URL;

/**
 * Class Authenticate
 * @package App\Http\Middleware
 */
class Authenticate
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * @var \Illuminate\Routing\ResponseFactory
     */
    private $response;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @param \Illuminate\Routing\ResponseFactory $response
     */
    public function __construct(Guard $auth, ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!Sentry::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response(json_encode([
                    'status' => 'info',
                    'message' => 'Необходимо авторизоваться'
                ]), 401);
            } else {
                session()->put('redirect', URL::full());
                session()->put('show_login_form', true);

                FlashMessages::add('info', 'Необходимо авторизоваться');

                return $this->response->redirectToRoute('login');
            }
        } else {
            if ($redirect = Session::get('redirect')) {
                Session::forget('redirect');

                return $this->response->redirectTo($redirect);
            }
        }

        return $next($request);
    }
}
