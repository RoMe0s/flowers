<?php

namespace App\Widgets\Hits;

use App\Models\Product;
use App\Models\Productable;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * Class MenuWidget
 * @package App\Widgets\Menu
 */
class HitsWidget extends Widget
{
    public function index()
    {

        $sets = Cache::remember('static_hits', 10, function () {

            $productables = Productable::visible()->positionSorted()->get();

            $groups = $productables->groupBy('productable_type');

            $result = collect();

            foreach ($groups as $class => $group) {

                $relations = array();

                if($class !== (string)Product::class) {

                    $relations = array('visible_flowers');

                }

                $result = $result->merge($class::visible()->with($relations)->whereIn('id', $group->pluck('productable_id')->toArray())->get());

            }

            return $result;

        });

        return view('widgets.set.index')->with(['static' => true, 'sets' => $sets])->render();
    }
}