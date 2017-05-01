<?php

namespace App\Widgets\Filter;


use App\Models\Box;
use App\Models\Flower;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;
use App\Models\FilterItem;

/**
 * Class MenuWidget
 * @package App\Widgets\Menu
 */
class FilterWidget extends Widget
{
    public function index()
    {

        return view('widgets.filter.index')->render();

    }

    public function price($slug) {
    
        $filters = Cache::remember('filter_items', 10, function() {

            return FilterItem::visible()->positionSorted('DESC')->get();
        
        });

    
        if(sizeof($filters)) {

            return view('widgets.filter.price', compact('filters', 'slug'))->render();
        
        }

    }
}
