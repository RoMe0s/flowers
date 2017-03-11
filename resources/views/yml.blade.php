<?php print '<?xml version="1.0" encoding="utf-8"?>'; ?>

<yml_catalog date="{{ date("Y-m-d H:i") }}">
    <shop>
        <name>Flowersmoscow24.ru</name>
        <company>ООО "Вдохновение"</company>
        <url>{{ env('APP_URL') }}</url>
        <currencies>
            <currency id="RUR" rate="1" />
        </currencies>
        <categories>
            @foreach($categories as $category)
                <category id="{{ $category->id }}">{{ $category->name }}</category>
            @endforeach
        </categories>
        <delivery-options>
            <option cost="350" days="1" />
        </delivery-options>
        <offers>
            @foreach($sets as $set)
                <offer id="{{ $set->id }}" available="true">
                    <url>
                        {!! url('/' . $set->category->slug . '?box=' . $set->box->id) !!}
                    </url>
                    <price>{{ $set->price }}</price>
                    <currencyId>RUR</currencyId>
                    <categoryId>{{ $set->box->category->id }}</categoryId>
                    <market_category>2061</market_category>
                    <market_category>2124</market_category>
                    <picture>{{ url('') . $set->image }}</picture>
                    <name>{{ $set->box->category->name.', Набор #'.$set->id }}</name>
                    <sales_notes>Необходима предоплата 50%</sales_notes>
                    <age>0</age>
                    <param name="Размер коробки" unit="см">{{ $set->box->length.' x '.$set->box->width }}</param>
                </offer>
            @endforeach

            @foreach($bouquets as $bouquet)
                <offer id="{{ $bouquet->id }}" available="true">
                    <url>{{ url($bouquet->category->slug) }}</url>
                    <price>{{ $bouquet->price }}</price>
                    <currencyId>RUR</currencyId>
                    <categoryId>{{ $bouquet->category->id }}</categoryId>
                    <market_category>2061</market_category>
                    <market_category>2124</market_category>
                    <picture>{{ url('') . $bouquet->image }}</picture>
                    <name>{{ $bouquet->name }}</name>
                    <sales_notes>Необходима предоплата 50%</sales_notes>
                    <age>0</age>
                </offer>
            @endforeach
        </offers>
    </shop>
</yml_catalog>
