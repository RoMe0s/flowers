<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 6/11/17
 * Time: 6:14 PM
 */
namespace App\Widgets\Banner;

use App\Models\Banner;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

class BannerWidget extends Widget
{

    public function index($position) {

        $banner = Cache::remember('widget_' . $position, 10, function() use ($position) {

            return Banner::with(['items', 'items.translations'])
                ->where('layout_position', $position)
                ->first();

        });

        if(isset($banner) && view()->exists('widgets.banner.templates.' . $banner->template . '.index')) {

            return view('widgets.banner.templates.' . $banner->template . '.index')->with([
                'banner' => $banner
            ])->render();

        }

        return "";

    }

}