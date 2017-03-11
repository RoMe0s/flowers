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

        // news
       $router->get('news', ['as' => 'news', 'uses' => 'Frontend\NewsController@index']);

       //shares
       $router->get('shares', ['as' => 'shares', 'uses' => 'Frontend\ShareController@index']);

        //subscriptions
        $router->get('subscriptions', [
            'as' => 'subscriptions',
            'uses' => 'Frontend\SubscriptionController@index'
        ]);

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

                $router->post(
                  '/',
                  ['as' => 'profile.post', 'uses' => 'Frontend\ProfileController@update']
                );

                $router->get(
                    'subscriptions',
                    ['as' => 'profile.subscriptions', 'uses' => 'Frontend\ProfileController@subscriptions']
                );

                $router->get(
                    'discounts',
                    ['as' => 'profile.discounts', 'uses' => 'Frontend\ProfileController@discounts']
                );
                $router->get(
                    'orders',
                    ['as' => 'profile.orders', 'uses' => 'Frontend\ProfileController@orders']
                );

                $router->get(
                    'addresses',
                    ['as' => 'profile.addresses', 'uses' => 'Frontend\ProfileController@addresses']
                );
            }
        );

        //products
        $router->get('/related-goods', [
            'as' => 'products',
            'uses' => 'Frontend\GoodsController@related'
        ]);

        //individual
        $router->get('/individual-goods', [
            'as' => 'individuals',
            'uses' => 'Frontend\GoodsController@individual'
        ]);

        $router->post('/feedback', [
            'as' => 'store.feedback',
            'uses' => 'ApiController@feedbackStore'
        ]);

        //api
        $router->group(['prefix' => 'api'], function () use ($router) {
           $router->post('help', [
               'as' => 'api.help',
               'uses' => 'ApiController@help'
           ]);

           $router->post('make/individual/order', [
               'as' => 'api.individual.store',
               'uses' => 'Frontend\GoodsController@storeIndividual'
           ]);

           $router->any('/market.yml', 'ApiController@marketYML');

           $router->post('api/feedback', [
               'as' => 'api.feedback'
           ]);

/*          Route::any('/get/box/sets', 'ApiController@getBoxSets');

            Route::any('/cart/add/set', 'CartController@addSet');
            Route::any('/cart/add/bouquet', 'CartController@addBouquet');
            Route::any('/cart/add/sale', 'CartController@addSale');
            Route::any('/cart/add/product', 'CartController@addProduct');*/
        });

        //auth
        $router->get('login', [
            'as' => 'login',
            'uses' => 'Frontend\AuthController@getLogin'
        ]);
        $router->post('login',[
           'as' => 'post.login',
            'uses' => 'Frontend\AuthController@postLogin'
        ]);

        $router->group(['middleware' => 'guest'], function() use ($router) {

            $router->get('reg', [
                'as' => 'reg',
                'uses' => 'Frontend\AuthController@getRegister'
            ]);
            $router->post('reg', [
                'as' => 'post.reg',
                'uses' => 'Frontend\AuthController@postRegister'
            ]);

            $router->get('password-reset', [
                'as' => 'password.reset',
                'uses' => 'Frontend\AuthController@getReset'
            ]);

            $router->post('password-reset', [
                'as' => 'post.password.reset',
                'uses' => 'Frontend\AuthController@postReset'
            ]);

        });

        //cart
        $router->get('cart', [
            'as' => 'cart',
            'uses' => 'Frontend\CartController@index'
        ]);


        // pages + categories
        $router->get(
            '/{slug}',
            ['as' => 'pages.show', 'uses' => 'Frontend\PageController@getPage']
        );

    }
);