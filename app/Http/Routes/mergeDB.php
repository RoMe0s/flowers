<?php

//basic

function changeID($table, $id) {
    \DB::update("ALTER TABLE $table AUTO_INCREMENT=$id;");
}

function setForeignCheck($enable = true) {
    if(!$enable) {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    } else {
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

function truncate($table, $id = null, $custom_field = null) {

    if(!$id) {

        setForeignCheck(false);

        \DB::table($table)->truncate();

        setForeignCheck();

    } else {

        $field = isset($custom_field) ? $custom_field : 'id';

        \DB::table($table)->where($field, '>=', $id)->delete();

    }

}

function builder($table, $id = null, $custom_field = null) {
    $builder = \DB::connection('old_mysql')->table($table);
    if($id) {
        $field = isset($custom_field) ? $custom_field : 'id';
        $builder->where($field, '>=', $id);
    }
    return $builder;
}

function removeFrom($table) {

}

//endbasic

function mergeUsers() { //table - users
    \DB::beginTransaction();

    truncate('users');
    truncate('user_info');

    setForeignCheck(false);

    builder('users')->chunk(100, function($users) {

        foreach($users as $user) {

            changeID('users', $user->id);

            \DB::table('users')->insert([
                'activated' => true, //default
                'email' => $user->email,
                'password' => $user->password,
                'discount' => $user->discount,
                'start_discount' => $user->start_discount,
                'notifications' => $user->notifications,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ]);

            \DB::table('user_info')->insert([
                'phone' => $user->phone,
                'name' => $user->name,
                'user_id' => $user->id
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();
}

function mergeUsersGroups() { //table users_groups

    \DB::beginTransaction();

    truncate('users_groups');

    setForeignCheck(false);

    builder('role_user')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            \DB::table('users_groups')->insert([
                'user_id' => $data_one->user_id,
                'group_id' => $data_one->role_id
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeUsersCodes() { //table users_codes

    \DB::beginTransaction();

    truncate('users_codes');

    setForeignCheck(false);

    builder('users_codes')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            \DB::table('users_codes')->insert([
                'user_id' => $data_one->user_id,
                'code_id' => $data_one->code_id,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeUsersSubscriptions() { // table users_subscriptions

    \DB::beginTransaction();

    truncate('users_subscriptions');

    setForeignCheck(false);

    builder('users_subscriptions')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            \DB::table('users_subscriptions')->insert([
                'user_id' => $data_one->user_id,
                'subscription_id' => $data_one->subscription_id,
                'paid_before' => $data_one->paid_before
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeSubscriptions($id = null) { //table subscriptions

    \DB::beginTransaction();

    truncate('subscriptions', $id);
    truncate('subscription_translations', $id, 'subscription_id');

    setForeignCheck(false);

    builder('subscriptions', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('subscriptions', $data_one->id);

            \DB::table('subscriptions')->insert([
                'image' => '/uploads/subscriptions/' . $data_one->image,
                'price' => $data_one->price
            ]);

            \DB::table('subscription_translations')->insert([
                'locale' => 'ru', //default
                'title' => $data_one->title,
                'content' => $data_one->desc,
                'subscription_id' => $data_one->id
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeSetsFlowers() { //table sets_flowers

    \DB::beginTransaction();

    truncate('sets_flowers');

    setForeignCheck(false);

    builder('sets_flowers')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            \DB::table('sets_flowers')->insert([
                'set_id' => $data_one->set_id,
                'flower_id' => $data_one->flower_id
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeSets($id = null) { //table sets

    \DB::beginTransaction();

    truncate('sets', $id);
    truncate('set_translations', $id, 'set_id');

    setForeignCheck(false);

    builder('sets', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('sets', $data_one->id);

            \DB::table('sets')->insert([
                'status' => $data_one->share, //default
                'position' => 0, //default
                'image' => '/uploads/sets/' . $data_one->image,
                'price' => $data_one->price,
                'box_id' => $data_one->box_id,
                'count'  => $data_one->count,
                'slug' => str_slug(str_random(10))
            ]);

            \DB::table('set_translations')->insert([
                'locale' => 'ru', //default
                'name' => 'Нужно заполнить',
                'set_id' => $data_one->id
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeSales($id = null) { //table sales

    \DB::beginTransaction();

    truncate('sales', $id);
    truncate('sale_translations', $id, 'sale_id');

    setForeignCheck(false);

    builder('sales', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('sales', $data_one->id);

            \DB::table('sales')->insert([
                'status' => true,
                'position' => 0,
                'publish_at' => $data_one->publish_date,
                'price' => $data_one->price,
                'image' => '/uploads/sales/' . $data_one->image,
                'created_at' => $data_one->created_at,
                'updated_at'  => $data_one->updated_at,
                'slug' => str_slug(str_random(10))
            ]);

            \DB::table('sale_translations')->insert([
                'locale' => 'ru', //default
                'name' => 'Нужно заполнить',
                'sale_id' => $data_one->id
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeProductsCategories() { //table products_categories

    \DB::beginTransaction();

    truncate('products_categories');

    setForeignCheck(false);

    builder('related_products_categories')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            \DB::table('products_categories')->insert([
                'product_id' => $data_one->product_id,
                'category_id' => $data_one->category_id,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeProducts($id = null) { //table products

    \DB::beginTransaction();

    truncate('products', $id);
    truncate('product_translations', $id, 'product_id');

    setForeignCheck(false);

    builder('related_products', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('products', $data_one->id);

            \DB::table('products')->insert([
                'slug' => str_slug($data_one->title), //auto
                'image' => '/uploads/products/' . $data_one->image,
                'size' => $data_one->size,
                'price' => $data_one->price,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at
            ]);

            \DB::table('product_translations')->insert([
                'locale' => 'ru', //default,
                'product_id' => $data_one->id,
                'name' => $data_one->title
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeOrders($id = null) { //table orders

    \DB::beginTransaction();

    truncate('orders', $id);
    truncate('order_items', $id, 'order_id');

    setForeignCheck(false);

    builder('orders', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('orders', $data_one->id);

            \DB::table('orders')->insert([
                'user_id' => $data_one->user_id,
                'courier_id' => $data_one->courier_id,
                'address_id' => $data_one->address_id,
                'delivery_price' => $data_one->delivery_price,
                'discount' => $data_one->discount,
                'prepay' => $data_one->prepay,
                'recipient_name' => $data_one->recipient_name,
                'recipient_phone' => $data_one->recipient_phone,
                'date' => $data_one->date,
                'time' => $data_one->time,
                'card_text' => $data_one->card_text,
                'desc' => $data_one->desc,
                'result' => $data_one->result,
                'status' => $data_one->status,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at,
                'deleted_at' => $data_one->deleted_at
            ]);

            $items = json_decode($data_one->items);

            foreach($items as $item) {

                if($item->options->category != "sets" && $item->options->category != "bouquets" && $item->options->category != "items" && $item->options->category != "sales") {
                    dd($item->options->category);
                }

                $item_type = null;

                switch ($item->options->category) {
                    case 'sets':
                        $item_type = "App\\Models\\Set";
                        break;
                    case 'bouquets':
                        $item_type = "App\\Models\\Bouquet";
                        break;
                    case 'items':
                        $item_type = "App\\Models\\Product";
                        break;
                    case 'sales':
                        $item_type = "App\\Models\\Sale";
                        break;
                }

                if($item_type) {

                    \DB::table('order_items')->insert([
                        'order_id' => $data_one->id, //default,
                        'count' => $item->qty,
                        'price' => $item->price,
                        'itemable_id' => $item->id,
                        'itemable_type' => $item_type
                    ]);

                }

            }

        }

    });

    setForeignCheck();

    \DB::commit();

    makeOrderImages();

}

function mergeIndividuals($id = null) { //table individuals

    \DB::beginTransaction();

    truncate('individuals', $id);

    setForeignCheck(false);

    builder('individual_items', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('individuals', $data_one->id);

            \DB::table('individuals')->insert([
                'phone'     => $data_one->phone,
                'email'     => $data_one->email,
                'image'     => '/uploads/image/' . str_replace(array('[', ']'), '', $data_one->image),
                'price'     => $data_one->price,
                'text'      => $data_one->text,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeFlowersColors() { //table flowers_colors

    \DB::beginTransaction();

    truncate('flowers_colors');

    setForeignCheck(false);

    builder('flowers_colors')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            \DB::table('flowers_colors')->insert([
                'flower_id'     => $data_one->flower_id,
                'color_id'     => $data_one->color_id,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeFlowers($id = null) { //table flowers

    \DB::beginTransaction();

    truncate('flowers', $id);
    truncate('flower_translations', $id, 'flower_id');

    setForeignCheck(false);

    builder('flowers', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('flowers', $data_one->id);

            \DB::table('flowers')->insert([
                'status'     => $data_one->in_stock
            ]);

            \DB::table('flower_translations')->insert([
                'locale'         => 'ru', //default
                'flower_id'     => $data_one->id,
                'title'     => $data_one->title,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeColors() { //table colors

    \DB::beginTransaction();

    truncate('colors');
    truncate('color_translations');

    setForeignCheck(false);

    builder('colors')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('colors', $data_one->id);

            \DB::table('colors')->insert([
                'hex'     => $data_one->hex
            ]);

            \DB::table('color_translations')->insert([
                'locale'         => 'ru', //default
                'color_id'     => $data_one->id,
                'title'     => $data_one->title,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeCodes() { //table codes

    \DB::beginTransaction();

    truncate('codes');

    setForeignCheck(false);

    builder('codes')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('codes', $data_one->id);

            \DB::table('codes')->insert([
                'status' => true, //default
                'code'     => $data_one->code,
                'discount' => $data_one->discount,
                'date'  => $data_one->date,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeCategories($id = null) { //table categories

    \DB::beginTransaction();

    truncate('categories', $id);
    truncate('category_translations', $id, 'category_id');

    setForeignCheck(false);

    builder('categories', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('categories', $data_one->id);

            \DB::table('categories')->insert([
                'image'     => '/uploads/categories/' . $data_one->image,
                'slug' => str_slug($data_one->title), //auto,
                'position' => 0, //default
                'status' => true, //default
            ]);

            \DB::table('category_translations')->insert([
                'locale'         => 'ru', //default
                'category_id'     => $data_one->id,
                'name'     => $data_one->title,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeBoxes($id = null) { //table boxes

    \DB::beginTransaction();

    truncate('boxes', $id);
    truncate('box_translations', $id, 'box_id');

    setForeignCheck(false);

    builder('boxes', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('boxes', $data_one->id);

            \DB::table('boxes')->insert([
                'category_id'     => $data_one->category_id,
                'image' => '/uploads/boxes/' . $data_one->image,
                'length' => $data_one->length,
                'width' => $data_one->width,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at
            ]);

            \DB::table('box_translations')->insert([
                'locale'         => 'ru', //default
                'box_id'     => $data_one->id,
                'title'     => $data_one->title,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeBouquetsFlowers() { //table bouquets_flowers

    \DB::beginTransaction();

    truncate('bouquets_flowers');

    setForeignCheck(false);

    builder('bouquets_flowers')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            \DB::table('bouquets_flowers')->insert([
                'bouquet_id'     => $data_one->bouquet_id,
                'flower_id'     => $data_one->flower_id
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeBouquets($id = null) { //table bouquets

    \DB::beginTransaction();

    truncate('bouquets', $id);
    truncate('bouquet_translations', $id, 'bouquet_id');

    setForeignCheck(false);

    builder('bouquets', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('bouquets', $data_one->id);

            \DB::table('bouquets')->insert([
                'image'     => '/uploads/bouquets/' . $data_one->image,
                'slug' => str_slug($data_one->title), //auto,
                'position' => 0, //default
                'status' => true, //default
                'price' => $data_one->price,
                'count' => $data_one->count,
                'category_id' => $data_one->type == 0 ? 7 : 5,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at
            ]);

            \DB::table('bouquet_translations')->insert([
                'locale'         => 'ru', //default
                'bouquet_id'     => $data_one->id,
                'name'     => $data_one->title,
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeNews($id = null) { //table news

    \DB::beginTransaction();

    truncate('news', $id);
    truncate('news_translations', $id, 'news_id');

    setForeignCheck(false);

    builder('articles', $id)->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('news', $data_one->id);

            \DB::table('news')->insert([
                'image'     => '/uploads/news/' . $data_one->image,
                'position' => 0, //default
                'status' => $data_one->publish,
                'publish_at' => $data_one->date,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at
            ]);

            \DB::table('news_translations')->insert([
                'locale'         => 'ru', //default
                'news_id'     => $data_one->id,
                'name'     => $data_one->title,
                'content' => $data_one->text
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function mergeAddresses() { //table addresses

    \DB::beginTransaction();

    truncate('addresses');

    setForeignCheck(false);

    builder('addresses')->chunk(100, function($data_list) {

        foreach($data_list as $data_one) {

            changeID('addresses', $data_one->id);

            $address = "";

            $address .= $data_one->city ? $data_one->city . ", " : "";

            $address .= $data_one->street ? $data_one->street . ", " : "";

            $address .= $data_one->house ? $data_one->house . ", " : "";

            $address .= $data_one->flat ? $data_one->flat : "";

            $address = trim($address, ',');

            \DB::table('addresses')->insert([
                'user_id'     => $data_one->user_id,
                'code'     => $data_one->code,
                'address' => $address,
                'created_at' => $data_one->created_at,
                'updated_at' => $data_one->updated_at
            ]);

        }

    });

    setForeignCheck();

    \DB::commit();

}

function createAdminForMe() {
    \Sentry::register(
        [
            'email'     => 'admin@admin.com',
            'password'  => 'admin',
            'activated' => 1,
        ]
    );

    // Assign user permissions
    $adminGroup = \Sentry::getGroupProvider()->findByName('Administrators');

    $adminUser = \Sentry::getUserProvider()->findByLogin('admin@admin.com');

    $adminUser->activated = true;
    $adminUser->save();

    $user_info = new \App\Models\UserInfo([
        'name' => 'admin',
    ]);
    $adminUser->info()->save($user_info);

    $adminUser->addGroup($adminGroup);
}

function makeOrderImages() {
    \DB::beginTransaction();

    \App\Models\Order::chunk(100, function($orders) {

        foreach($orders as $order) {

            $result_images = array();

            if($order->result) {

                foreach ($order->result as $image) {

                    if(strpos($image, '/uploads/orders/') === FALSE) {

                        $result_images[] = '/uploads/orders/' . $image;

                    }

                }

            }

            if(!empty($result_images)) {

                $order->result = $result_images;

                $order->save();

            }

        }

    });

    \DB::commit();
}

$router->group(['prefix' => 'mergeDB'], function() use ($router) {

    $router->get('/', function () {
        abort(404);
        mergeUsers();
        mergeUsersCodes();
        mergeUsersSubscriptions();
        mergeSubscriptions();
        mergeSetsFlowers();
        mergeSets();
        mergeSales();
        mergeProductsCategories();
        mergeProducts();
        mergeOrders();
        mergeIndividuals();
        mergeFlowersColors();
        mergeFlowers();
        mergeColors();
        mergeCodes();
        mergeCategories();
        mergeBoxes();
        mergeBouquetsFlowers();
        mergeBouquets();
        mergeNews();
        mergeAddresses();
        mergeUsersGroups();
        createAdminForMe();
        return "<h1>Well Done!</h1>";
    });

    //END MERGE (FIRST INIT)

    $router->get('compare', function(){
        abort(404);
        mergeUsers();//good
        mergeUsersCodes();//good
        mergeUsersSubscriptions();//good
        mergeSubscriptions(7);
        mergeSetsFlowers();//good
        mergeSets(222);
        mergeSales(68);
        mergeProductsCategories();//good
        mergeProducts(11);
        mergeOrders();//good
        mergeIndividuals(22);
        mergeFlowersColors();//good
        mergeFlowers(91);
        mergeColors();//good
        mergeCodes();//good
        mergeCategories(8);
        mergeBoxes(22);
        mergeBouquetsFlowers();//good
        mergeBouquets(44);
        mergeNews();//good
        mergeAddresses();//good
        mergeUsersGroups();//good
        createAdminForMe();//good
        return "<h1>Well Done!</h1>";
    });

});