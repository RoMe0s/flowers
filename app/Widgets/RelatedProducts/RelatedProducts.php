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
    public function index($category_id)
    {

        Cache::flush();

        $products = Cache::remember('news', 5, function() use ($category_id) {
            return Product::whereHas('categories', function ($query) use ($category_id) {
                return $query->where('id', $category_id);
            })
                ->with('translations')
                ->visible()
                ->orderBy('price', 'DESC')
                ->paginate(12);
        });

        return view('widgets.relatedproducts.index', compact('products'))->render();

    }
}