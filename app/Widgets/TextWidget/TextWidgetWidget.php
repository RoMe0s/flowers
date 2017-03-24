<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Widgets\TextWidget;

use Pingpong\Widget\Widget;
use App\Models\TextWidget;
use Illuminate\Support\Facades\Cache;

/**
 * Class TextWidgetWidget
 * @package App\Widgets\TextWidget
 */
class TextWidgetWidget extends Widget
{
    /**
     * @param string $position
     */
    function index($position)
    {

        $widget = Cache::remember('text_widget_' . $position, 10, function() use($position) {

            return TextWidget::with(['translations'])->where('layout_position', $position)->visible()->first();

        });

        if($widget) {
            return $widget->content;
        }
    }
}
 