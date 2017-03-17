<?php

namespace App\Widgets\RelatedProducts;

use App\Models\News;
use App\Models\Product;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * @package App\Widgets\Menu
 */
class RelatedProducts extends Widget
{
    /**
     *
     * @return $this
     */
    public function index($category_id, $model = null)
    {

        $current_id = null;

        if(isset($model) && $model instanceof Product) {
            $current_id = $model->id;
        }

        Cache::flush();

        $products = Cache::remember('category_' . $category_id.$current_id, 5, function() use ($category_id, $current_id) {
            if($category_id != -1) {
                $query = Product::whereHas('categories', function ($query) use ($category_id) {
                    return $query->where('id', $category_id);
                })
                    ->with('translations')
                    ->visible()
                    ->orderBy('price', 'DESC');
            } else {

                $query = Product::with('translations')
                    ->visible()
                    ->orderBy(\DB::raw('RAND()'))
                    ->orderBy('price', 'DESC');

            }

            if(isset($current_id)) {
                $query->where('id', '<>', $current_id);
            }

            return $query->paginate(12);

        });

        return view('widgets.relatedproducts.index', compact('products'))->render();

    }
}