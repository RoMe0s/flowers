<?php

namespace App\Widgets\Category;

use App\Models\Category;
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

        Cache::flush();

        $categories = Cache::remember('categories', 5, function() {
            return Category::visible()->positionSorted()->with('translations')->get();
        });

        if($categories) {
            return view('widgets.category.index', compact('categories'))->render();
        }

    }
}