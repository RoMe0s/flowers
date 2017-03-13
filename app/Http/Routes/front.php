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

            $router->any('/cart/add/set', 'Frontend\CartController@addSet');
            $router->any('/cart/add/bouquet', 'Frontend\CartController@addBouquet');
            $router->any('/cart/add/sale', 'Frontend\CartController@addSale');
            $router->any('/cart/add/product', 'Frontend\CartController@addProduct');
           
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

            // Socialite routes
            $router->any('/login/{provider}/redirect', 'Frontend\AuthController@socialiteRedirect');
            $router->any('/login/{provider}/callback', 'Frontend\AuthController@socialiteCallback');


//            $router->get('password-reset', [
//                'as' => 'password.reset',
//                'uses' => 'Frontend\AuthController@getReset'
//            ]);
//
//            $router->post('password-reset', [
//                'as' => 'post.password.reset',
//                'uses' => 'Frontend\AuthController@postReset'
//            ]);

        });

        //cart


        $router->get('cart', [
            'as' => 'cart',
            'uses' => 'Frontend\CartController@index'
        ]);

        $router->group(['middleware' => 'cart', 'prefix' => 'cart'], function () use ($router) {
//            $router->get('/make/fast/order', 'Frontend\OrderController@getFast');
//            $router->post('/make/fast/order', 'Frontend\OrderController@makeFast');

            $router->any('/{id}/qty/plus', 'Frontend\CartController@qtyPlus');
            $router->any('/{id}/qty/minus', 'Frontend\CartController@qtyMinus');
            $router->any('/{item}/{id}/remove', 'Frontend\CartController@removeItem');
            $router->any('/clear', 'Frontend\CartController@clear');

            $router->group(['middleware' => ['auth']], function () use ($router) {
//                $router->get('/make/order', 'Frontend\OrderController@create');
//                $router->post('/make/order', 'Frontend\OrderController@store');
                $router->any('/apply/code', 'Frontend\CartController@applyCode');
            });

        });
//        Route::get('/order/{id}/pay', 'OrderController@show');
//        Route::any('/order/{id}/cancel', 'OrderController@destroy');
//
//        Route::any('/order/success', 'OrderController@success');
//        Route::any('/order/fail', 'OrderController@fail');


        // pages + categories
        $router->get(
            '/{slug}',
            ['as' => 'pages.show', 'uses' => 'Frontend\PageController@getPage']
        );

    }
);