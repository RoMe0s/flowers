<?php

namespace App\Http\Controllers\Frontend;

class ProductController extends FrontendController
{

    public $module = 'product';

    public function show($category, $slug) {

        $model = find_product($category, $slug);

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        return $this->render('product');

    }
}
