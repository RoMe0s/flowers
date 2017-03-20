<?php

namespace App\Widgets\Filter;


use App\Models\Box;
use App\Models\Flower;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * Class MenuWidget
 * @package App\Widgets\Menu
 */
class FilterWidget extends Widget
{
    public function index()
    {

        $boxes = Cache::remember('filter_boxes', 5, function() {
            return Box::all();
        });

        $flowers = Cache::remember('filter_flowers', 5, function() {
            return Flower::all();
        });

        return view('widgets.filter.index', compact('boxes', 'flowers'))->render();
    }
}