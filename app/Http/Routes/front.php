<?php

/*$router->get('/test_login', function () {

    return view('test_login')->render();

});

$router->post('/test_login', function (){

    if(request()->get('password') == "123!321") {

        session()->put('allow_test_site',  true);

        return redirect()->to(route('home'));

    }

    return redirect()->back();

});*/


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

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->any('/subscribe/{id}', 'Frontend\OrderController@subscribe');
            $router->any('/subscription/{id}/pay', 'Frontend\OrderController@makeSubscription');
        });

        //flowers
        $router->get('/flowers', [
            'as' => 'flowers',
            'uses' => 'Frontend\FlowerController@index'
        ]);
        $router->get('/flowers/sort/{sort}', [
            'as' => 'flowers_sort',
            'uses' => 'Frontend\FlowerController@index'
        ]);
        $router->get('/flowers-reload', [
            'as' => 'flowers_reload',
            'uses' => 'Frontend\FlowerController@reload',
            'middleware' => 'ajax'
        ]);
        $router->get('/flowers-reload/sort/{sort}', [
           'uses' => 'Frontend\FlowerController@reload',
           'middleware' => 'ajax'
        ]);

        //presents
        $router->get('/related-goods', [
            'as' => 'presents',
            'uses' => 'Frontend\PresentsController@index'
        ]);
        $router->get('/related-goods/sort/{sort}', [
            'as' => 'presents_sort',
            'uses' => 'Frontend\PresentsController@index'
        ]);
        $router->get('/related-goods-reload', [
            'as' => 'presents_reload',
            'uses' => 'Frontend\PresentsController@reload',
            'middleware' => 'ajax'
        ]);
        $router->get('/related-goods-reload/sort/{sort}', [
            'uses' => 'Frontend\PresentsController@reload',
            'middleware' => 'ajax'
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
        $router->any('logout', [
           'as' => 'logout',
           'uses' => 'Frontend\AuthController@logout'
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


            $router->get('password-reset', [
                'as' => 'password.reset',
                'uses' => 'Frontend\AuthController@getReset'
            ]);
//
            $router->post('password-reset', [
                'as' => 'post.password.reset',
                'uses' => 'Frontend\AuthController@postReset'
            ]);

            $router->post('password-reset-phone', 'Frontend\AuthController@postResetPhone');

            $router->get('password-reset/{token}/{login}', [
                'as' => 'password.token',
                'uses' => 'Frontend\AuthController@getRestore'
            ]);

            $router->post('password-reset/{token}/{login}', [
                'as' => 'post.password.token',
                'uses' => 'Frontend\AuthController@postRestore'
            ]);

        });

        //cart
        $router->group(['prefix' => 'cart'], function () use ($router) {

            $router->get('/', [
                'as' => 'cart',
                'uses' => 'Frontend\CartController@index'
            ]);

            $router->group(['middleware' => ['auth', 'cart']], function () use ($router) {

                $router->get('/make/order', [
                    'as' => 'get.order',
                    'uses' => 'Frontend\OrderController@create'
                ]);
                $router->post('/make/order', [
                    'as' => 'post.order',
                    'uses' => 'Frontend\OrderController@store'
                ]);

            });

            $router->group(['middleware' => 'cart'], function() use ($router) {
                $router->get('/make/fast/order', [
                    'as' => 'get.fast.order',
                    'uses' => 'Frontend\OrderController@getFast'
                ]);
                $router->post('/make/fast/order', [
                    'as'   => 'post.fast.order',
                    'uses' => 'Frontend\OrderController@makeFast'
                ]);
                $router->any('/clear', 'Frontend\CartController@clear');
                $router->any('/{id}/qty/plus', 'Frontend\CartController@qtyPlus');
                $router->any('/{id}/qty/minus', 'Frontend\CartController@qtyMinus');
                $router->any('/{item}/{id}/remove', 'Frontend\CartController@removeItem');
                $router->any('/apply/code', 'Frontend\CartController@applyCode');
            });

        });

        //pay
        $router->get('/order/{id}/pay', [
            'as' => 'order.pay',
            'uses' => 'Frontend\OrderController@show'
        ]);
        $router->any('/order/{id}/cancel', 'Frontend\OrderController@destroy');
        $router->any('/order/success', 'Frontend\OrderController@success');
        $router->any('/order/fail', 'Frontend\OrderController@fail');

        // products
        $router->get('/{category}/{slug}',[
            'as' => 'product.show',
            'uses' => 'Frontend\ProductController@show'
        ]);


        // pages + categories
        $router->get(
             '/{slug}',
             ['as' => 'pages.show', 'uses' => 'Frontend\PageController@getPage']
        );
        $router->get('/{slug}/sort/{sort}', [
            'as' => 'pages.sort',
            'uses' => 'Frontend\PageController@getPage'
        ]);

    }
);
