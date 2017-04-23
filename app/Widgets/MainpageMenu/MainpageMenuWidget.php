<?php

/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 21.04.17
 * Time: 20:46
 */

namespace App\Widgets\MainpageMenu;

use App\Models\Category;
use App\Models\MainPageMenu;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

class MainpageMenuWidget extends Widget
{

    private function _processCategory($item) {

        $category = null;

        Category::visible()
            ->where('id', $item->menuable_id)
            ->with([
                'translations',
                'visible_directProducts',
                'visible_children',
                'visible_children.visible_directProducts',
                'boxes.visible_sets',
                'boxes.visible_sets.visible_flowers',
                'visible_bouquets',
                'visible_bouquets.visible_flowers',
                'visible_children.visible_bouquets',
                'visible_children.visible_bouquets.visible_flowers',
                'visible_children.boxes',
                'visible_children.boxes.visible_sets',
                'visible_children.boxes.visible_sets.visible_flowers'
            ])
            ->chunk(100, function($categories) use (&$category) {

                    foreach($categories as $real_category) {

                        $type = explode("\\", $real_category->type);

                        $method = '_load' . array_pop($type);

                        $real_category->products = $this->{$method}($real_category);

                    }

                    $category = $real_category;

            });

        return $category;

    }

    private function _randomize($category) {

        if (sizeof($category->products)) {

            $count = $category->products->count() > 4 ? 4 : $category->products->count();

            $category->products = $category->products->shuffle()->take($count);

        }

        return $category;

    }

    public function index() {

        $list = Cache::remember('mainpage_menu', 10, function() {

            $list = MainPageMenu::with(['menuable'])->visible()->positionSorted()->get();

            $list->map(function ($item) {

                if($item->menuable_type == (string)Category::class) {

                    $item->data = $this->_processCategory($item);

                } else {

                    $item->data = $item->menuable;

                }

                return $item;

            });

            $list = $list->sortBy('position');

            return $list;

        });

        $list->where('menuable_type', (string)Category::class)->map(function($item) {

            $item->data = $this->_randomize($item->data);

            return $item;

        });

        return view('widgets.mainpage_menu.index')->with(['list' => $list])->render();

    }

    private function _loadSet(Category $model) {

        $products = collect();

        foreach($model->getChildren(true) as $child) {

            $child->boxes->each(function($box) use (&$products) {

                $products = $products->merge($box->visible_sets);

            });

        }

        return $products;

    }

    private function _loadProduct(Category $model) {

        $products = collect();

        foreach($model->getChildren(true) as $child) {

            $products = $products->merge($child->visible_directProducts);

        }

        return $products;

    }

    private function _loadBouquet(Category $model) {

        $products = collect();

        foreach ($model->getChildren(true) as $child) {

            $products = $products->merge($child->visible_bouquets);

        }

        return $products;

    }

}