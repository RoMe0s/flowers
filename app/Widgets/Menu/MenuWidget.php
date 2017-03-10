<?php

namespace App\Widgets\Menu;

use App\Models\Menu;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * Class MenuWidget
 * @package App\Widgets\Menu
 */
class MenuWidget extends Widget
{
    /**
     * @param string $position
     *
     * @return $this
     */
    public function index($position)
    {

        Cache::flush();

        $menu = Cache::remember('menu_' . $position, 10, function() use ($position) {
            return Menu::with(['translations', 'visible_items'])
                ->whereLayoutPosition($position)
                ->visible()
                ->first();
        });

        if(isset($menu) && view()->exists('widgets.menu.templates.' . $menu->template . '.index')) {

            return view('widgets.menu.templates.' . $menu->template . '.index')->with(compact('menu'))->render();
        }
    }
}