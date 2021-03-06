<?php
$router->group(

    ['prefix' => 'admin'],
    function ($router) {
        $router->group(
            ['middleware' => 'admin.auth'],
            function ($router) {
                $router->any('/', ['as' => 'admin.home', 'uses' => 'Backend\BackendController@getIndex']);
                $router->any('/home', 'Backend\BackendController@getIndex');

                // users
                $router->post(
                    'user/{id}/ajax_field',
                    array (
                        'middleware' => ['ajax'],
                        'as'         => 'admin.user.ajax_field',
                        'uses'       => 'Backend\UserController@ajaxFieldChange',
                    )
                );
                $router->get(
                    'user/new_password/{id}',
                    ['as' => 'admin.user.new_password.get', 'uses' => 'Backend\UserController@getNewPassword']
                );
                $router->post(
                    'user/new_password/{id}',
                    ['as' => 'admin.user.new_password.post', 'uses' => 'Backend\UserController@postNewPassword']
                );
                $router->resource('user', 'Backend\UserController');
                $router->get('users/find', 'Backend\UserController@find');
                $router->get('products/find', 'Backend\ProductController@find');

                // groups
                $router->resource('group', 'Backend\GroupController');

                // pages
                $router->post(
                    'page/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.page.ajax_field',
                        'uses'       => 'Backend\PageController@ajaxFieldChange',
                    ]
                );
                $router->resource('page', 'Backend\PageController');


                // subscriptions
                $router->post(
                    'subscription/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.subscription.ajax_field',
                        'uses'       => 'Backend\SubscriptionController@ajaxFieldChange',
                    ]
                );
                $router->resource('subscription', 'Backend\SubscriptionController');

                //categories
                $router->post(
                    'category/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.category.ajax_field',
                        'uses'       => 'Backend\CategoryController@ajaxFieldChange',
                    ]
                );
                $router->resource('category', 'Backend\CategoryController');

                //boxes
                $router->resource('box', 'Backend\BoxController');

                //colors
                $router->resource('color', 'Backend\ColorController');

                //flowers
                $router->post(
                    'flower/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.flower.ajax_field',
                        'uses'       => 'Backend\FlowerController@ajaxFieldChange',
                    ]
                );
                $router->resource('flower', 'Backend\FlowerController');

                //codes
                $router->post(
                    'code/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.code.ajax_field',
                        'uses'       => 'Backend\CodeController@ajaxFieldChange',
                    ]
                );
                $router->resource('code', 'Backend\CodeController');

                //individuals
                $router->resource('individual', 'Backend\IndividualController',
                    ['except' => ['create', 'store']]
                );

                //discount
                $router->resource('discount', 'Backend\DiscountController');

                //products
                $router->post(
                    'product/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.product.ajax_field',
                        'uses'       => 'Backend\ProductController@ajaxFieldChange',
                    ]
                );
                $router->resource('product', 'Backend\ProductController');

                //bouquets
                $router->post(
                    'bouquet/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.bouquet.ajax_field',
                        'uses'       => 'Backend\BouquetController@ajaxFieldChange',
                    ]
                );
                $router->resource('bouquet', 'Backend\BouquetController');

                //sales
                $router->post(
                    'sale/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.sale.ajax_field',
                        'uses'       => 'Backend\SaleController@ajaxFieldChange',
                    ]
                );
                $router->resource('sale', 'Backend\SaleController');

                //sets
                $router->post(
                    'set/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.set.ajax_field',
                        'uses'       => 'Backend\SetController@ajaxFieldChange',
                    ]
                );
                $router->resource('set', 'Backend\SetController');

                //productables
                 $router->post(
                        'productable/{id}/ajax_field',
                        [
                            'middleware' => ['ajax'],
                            'as'         => 'admin.productable.ajax_field',
                            'uses'       => 'Backend\ProductableController@ajaxFieldChange',
                        ]
                 );
                $router->get('productable/load', 'Backend\ProductableController@ajaxLoad');
                $router->resource('productable', 'Backend\ProductableController');

                $router->post(
                    'mainpagemenu/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.mainpagemenu.ajax_field',
                        'uses'       => 'Backend\MainPageMenuController@ajaxFieldChange',
                    ]
                );
                $router->get('mainpagemenu/load', 'Backend\MainPageMenuController@ajaxLoad');
                $router->resource('mainpagemenu', 'Backend\MainPageMenuController');

                //filter items
                $router->post(
                    'filteritem/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.filteritem.ajax_field',
                        'uses'       => 'Backend\FilterItemController@ajaxFieldChange',
                    ]
                );
                $router->resource('filteritem', 'Backend\FilterItemController');

                //banners
                $router->post(
                    'banner/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.banner.ajax_field',
                        'uses'       => 'Backend\BannerController@ajaxFieldChange',
                    ]
                );
                $router->resource('banner', 'Backend\BannerController');


                //orders
                $router->post(
                    'order/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.order.ajax_field',
                        'uses'       => 'Backend\OrderController@ajaxFieldChange',
                    ]
                );
                $router->resource('order', 'Backend\OrderController');
                $router->get(
                    '/order/items/add', 'Backend\OrderController@addBasketItemToOrder'
                );
                $router->get('basket', [
                    'as' => 'admin.basket',
                    'uses' =>  'Backend\OrderController@basket'
                ]);
                $router->get('order/status/change', 'Backend\OrderController@changeStatus');
                $router->get('order/items/reload', 'Backend\OrderController@reloadItems');


                //basket
                $router->group(['prefix' => 'basket'], function () use ($router){
                   $router->get('/add', 'Backend\OrderController@add');
                   $router->get('/remove', 'Backend\OrderController@remove');
                   $router->get('/add/order', 'Backend\OrderController@addToOrder');
                });

                // news
                $router->post(
                    'news/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.news.ajax_field',
                        'uses'       => 'Backend\NewsController@ajaxFieldChange',
                    ]
                );
                $router->resource('news', 'Backend\NewsController');

                // menus
                $router->post(
                    'menu/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.menu.ajax_field',
                        'uses'       => 'Backend\MenuController@ajaxFieldChange',
                    ]
                );
                $router->resource('menu', 'Backend\MenuController');

                // text_widgets
                $router->post(
                    'text_widget/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.text_widget.ajax_field',
                        'uses'       => 'Backend\TextWidgetController@ajaxFieldChange',
                    ]
                );
                $router->resource('text_widget', 'Backend\TextWidgetController');

                // variables
                $router->post(
                    'variable/{id}/ajax_field',
                    array (
                        'middleware' => ['ajax'],
                        'as'         => 'admin.variable.ajax_field',
                        'uses'       => 'Backend\VariableController@ajaxFieldChange',
                    )
                );
                $router->get(
                    'variable/value/index',
                    ['as' => 'admin.variable.value.index', 'uses' => 'Backend\VariableController@indexValues']
                );
                $router->post(
                    'variable/value/update',
                    [
                        'middleware' => ['ajax'],
                        'as' => 'admin.variable.value.update',
                        'uses' => 'Backend\VariableController@updateValue'
                    ]
                );
                $router->resource('variable', 'Backend\VariableController');
            }
        );

        $router->group(
            ['prefix' => 'auth'],
            function ($router) {
                $router->get('login', ['as' => 'admin.login', 'uses' => 'Backend\AuthController@getLogin']);
                $router->post('login', ['as' => 'admin.login.post', 'uses' => 'Backend\AuthController@postLogin']);
                $router->get('logout', ['as' => 'admin.logout', 'uses' => 'Backend\AuthController@getLogout']);
            }
        );
    }
);
