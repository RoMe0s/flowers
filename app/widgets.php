<?php

Widget::register('widget__text_widget', 'App\Widgets\TextWidget\TextWidgetWidget@index');

Widget::register('widget__menu', 'App\Widgets\Menu\MenuWidget@index');

Widget::register('widget__news', 'App\Widgets\News\NewsWidget@index');

Widget::register('widget__category', 'App\Widgets\Category\CategoryWidget@index');

Widget::register('widget__set', 'App\Widgets\Set\SetWidget@index');

Widget::register('widget__category_filter', 'App\Widgets\Filter\FilterWidget@index');

Widget::register('widget__related_products', 'App\Widgets\RelatedProducts\RelatedProducts@index');