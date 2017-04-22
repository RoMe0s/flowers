<?php

/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 21.04.17
 * Time: 20:46
 */

namespace App\Widgets\MainpageMenu;

use App\Models\Bouquet;
use App\Models\Category;
use App\Models\Flower;
use App\Models\Set;
use Pingpong\Widget\Widget;

class MainpageMenuWidget extends Widget
{

    public function index() {

        $categories = collect();

        Category::visible()
            ->whereNull('parent_id')
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
            ->positionSorted()->chunk(100, function($real_categories) use (&$categories) {

                foreach($real_categories as $real_category) {

                    $type = explode("\\", $real_category->type);

                    $method = '_load' . array_pop($type);

                    $real_category->products = $this->{$method}($real_category);

                    if(sizeof($real_category->products)) {

                        $count = $real_category->products->count() > 4 ? 4 : $real_category->products->count();

                        $real_category->products = $real_category->products->shuffle()->take($count);

                        $priority = null;

                        switch ($real_category->type) {

                            case (string)Set::class:

                                $priority = 2;

                                break;

                            case (string)Bouquet::class:

                                $priority = 1;

                                break;

                            default:

                                $priority = 0;

                                break;

                        }

                        $real_category->priority = $priority;

                        $categories->push($real_category);

                    }

                }

            });

        $categories = $categories->sortByDesc('priority');

//        $flowers = Flower::joinTranslations('flowers')->visible()->lists('title', 'flower_id')->toArray();

        return view('widgets.mainpage_menu.index')->with(['categories' => $categories])->render();

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