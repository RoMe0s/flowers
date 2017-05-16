<?php

namespace App\Widgets\Category;

use App\Models\Category;
use App\Models\Product;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * @package App\Widgets\Menu
 */
class CategoryWidget extends Widget
{
    /**
     * @return $this
     */
    public function index()
    {
        $categories = Cache::remember('categories', 10, function() {
            return Category::where('type', '<>', (string)Product::class)->visible()->positionSorted()->with('translations')->get();
        });

        if($categories) {
            return view('widgets.category.index', compact('categories'))->render();
        }

    }
}