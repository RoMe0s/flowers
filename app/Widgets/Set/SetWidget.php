<?php

namespace App\Widgets\Set;

use App\Models\Set;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * @package App\Widgets\Menu
 */
class SetWidget extends Widget
{
    /**
     * @return $this
     */
    public function index($count = 4)
    {

//        $sets = Cache::remember('random_sets', 5, function() use ($count) {
            $sets = Set::visible()->with(['translations', 'visible_flowers', 'box'])->inRandomOrder()->limit($count)->get();
//        });

        return view('widgets.set.index', compact('sets'))->render();

    }
}