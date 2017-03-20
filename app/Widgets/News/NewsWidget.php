<?php

namespace App\Widgets\News;

use App\Models\News;
use Pingpong\Widget\Widget;
use Illuminate\Support\Facades\Cache;

/**
 * @package App\Widgets\Menu
 */
class NewsWidget extends Widget
{
    /**
     *
     * @return $this
     */
    public function index()
    {

        $news = Cache::remember('news', 5, function() {
           return News::with('translations')->visible()->publishAtSorted()->limit(4)->get();
        });

        return view('widgets.news.index', compact('news'))->render();

    }
}