<?php

namespace App\Http;

use App\Http\Middleware\TestMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                    => \App\Http\Middleware\Authenticate::class,
        'admin.auth'              => \App\Http\Middleware\AdminAuthenticate::class,
        'auth.basic'              => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'                   => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'                => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'ajax'                    => \App\Http\Middleware\AjaxMiddleware::class,
        'prepare.phone'           => \App\Http\Middleware\PreparePhone::class,
        'slug.set'                => \App\Http\Middleware\SetSlug::class,
        'localizationRedirect'    => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        'localeSessionRedirect'   => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
        'cart'                    => \App\Http\Middleware\NotEmptyCart::class,
        'test'                    => TestMiddleware::class
    ];
}
