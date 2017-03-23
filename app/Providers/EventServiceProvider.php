<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],

        'App\Events\Frontend\UserRegister' => [
//            'App\Listeners\Events\Frontend\SendAdminEmailAboutNewUser',
            'App\Listeners\Events\Frontend\SendUserActivationEmail',
        ],

        'App\Events\Frontend\IndividualStored' => [
            'App\Listeners\Events\Frontend\SendAdminAboutNewIndividual',
        ],
        SocialiteWasCalled::class => [
            'SocialiteProviders\VKontakte\VKontakteExtendSocialite@handle',
            'SocialiteProviders\Instagram\InstagramExtendSocialite@handle',
        ],
        'App\Events\Frontend\FastOrderStored' => [
            'App\Listeners\Events\Frontend\SendAdminAboutNewOrder'
        ],
        'Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse' => [
            'App\Listeners\Events\Pay\CheckOrderRequisites',
        ],
        'Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse' => [
            'App\Listeners\Events\Pay\ChangeOrderStatusWhenPaymentSuccessful',
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
        //
    }
}
