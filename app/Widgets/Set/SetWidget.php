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

        Cache::flush();

        $sets = Cache::remember('random_sets', 5, function() use ($count) {
            return Set::visible()->with(['translations', 'visible_flowers', 'box'])->limit($count)->inRandomOrder()->get();
        });

        return view('widgets.set.index', compact('sets'))->render();

    }
}