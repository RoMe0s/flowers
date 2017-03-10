<?php

$router->group(
    [
        'prefix'     => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect'],
    ],
    function () use ($router) {
        // home
        $router->any('/', ['as' => 'home', 'uses' => 'Frontend\PageController@getHome']);

        // 404
        $router->any('/not-found', ['as' => 'not_found', 'uses' => 'Frontend\PageController@notFound']);

        // pages
        $router->get(
            '/pages/{slug1?}/{slug2?}/{slug3?}/{slug4?}/{slug5?}',
            ['as' => 'pages.show', 'uses' => 'Frontend\PageController@getPage']
        );

        // news
//        $router->get('news', ['as' => 'news.index', 'uses' => 'Frontend\NewsController@index']);
//        $router->get('news/{slug}', ['as' => 'news.show', 'uses' => 'Frontend\NewsController@show']);

        $router->get('cart', [
            'as' => 'cart',
            'uses' => 'Frontend\CartController@index'
        ]);

        $router->group([], function() use ($router) {

            $router->get('login', [
                'as' => 'login',
                'uses' => 'Frontend\AuthController@getLogin'
            ]);

            $router->get('reg', [
                'as' => 'reg',
                'uses' => 'Frontend\AuthController@getRegister'
            ]);

        });

        // profiles
        $router->group(
            [
                'prefix'     => 'profile',
                'middleware' => 'auth',
            ],
            function () use ($router) {
                $router->get(
                    '/',
                    ['as' => 'profile', 'uses' => 'Frontend\ProfileController@index']
                );

                $router->get(
                    'subscriptions',
                    ['as' => 'profile.subscriptions', 'uses' => 'Frontend\ProfileController@subscriptions']
                );

                $router->get(
                    'discounts',
                    ['as' => 'profile.discounts', 'uses' => 'Frontend\ProfileController@discounts']
                );
                $router->post(
                    'orders',
                    ['as' => 'profile.orders', 'uses' => 'Frontend\ProfileController@orders']
                );

                $router->get(
                    'addresses',
                    ['as' => 'profile.addresses', 'uses' => 'Frontend\ProfileController@addresses']
                );
                $router->post(
                    'update/password',
                    ['as' => 'profiles.update.password', 'uses' => 'Frontend\ProfileController@updatePassword']
                );
            }
        );
    }
);