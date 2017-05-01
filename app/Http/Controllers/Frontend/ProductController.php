<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Bouquet;
use App\Models\Category;
use App\Models\Product;
use App\Models\Set;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends FrontendController
{

    public $module = 'product';

    public function show($scategory, $slug) {

        $model = find_product($scategory, $slug);

        abort_if(!$model, 404);

        $this->data('model', $model);

        $content = null;

        switch ($scategory) {
            case 'shares':
                $category = new Collection();
                $category->name = 'Акции';
                $category->slug = $scategory;
                $content = view('partials.content.sale', compact('model'))->render();
                break;
            default:
                $category = Category::with(['translations', 'visible_parent'])->where('slug', $scategory)->first();
                if($category->type == (string)Set::class) {
                    $content = view('partials.content.set', compact('model'))->render();
                } elseif($category->type == (string)Bouquet::class) {
                    $content = view('partials.content.bouquet', compact('model'))->render();
                } elseif($category->type == (string)Product::class) {
                    $content = view('partials.content.product', compact('model'))->render();
                }
                break;
        }

        abort_if(!$category, 404);

        $this->data('content', $content);

        $this->fillMeta($model, $this->module);

        $this->setBreadcrumbs($model, $category);

        $this->data('category', $category);

        return $this->render('product');

    }


    public function setBreadcrumbs($model, $category) {

        try {

            foreach($category->getParents(true) as $parent) {

                $this->breadcrumbs(
                    $parent->name,
                    route('pages.show', ['slug' => $parent->slug])
                );

            }

        } catch (\Exception $e) {}

        $this->breadcrumbs($category->name, route('pages.show', ['slug' => $category->slug]));

        $this->breadcrumbs($model->name);

    }

}
