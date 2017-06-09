@extends('layouts.master')

@section('content')
    <section class="after-order">
        <h1 class="text-success text-center">
            Заказ успешно оформлен
        </h1>
        <h4 class="text-center">
            В ближайшее время с вами свяжется оператор для подтверждения заказа<br />
            <small>
                После подтверждения заказа оператором станет доступной оплата
            </small>
        </h4>
        <h3 class="text-center">
            Информация
        </h3>
        <div class="text-center">
            <p class="info">
                Заказ <span class="text-info">№ {{$order->id}}</span><br />
                Общая сумма заказа <span class="text-success">{{$order->getTotal()}} руб.</span>
            </p>
        </div>
        <ul class="list-group col-sm-8 col-sm-offset-2 col-xs-12">
            @foreach($order->items as $item)
            <li class="list-group-item clearfix">
                <img src="{!! thumb($item->itemable->image, 75) !!}" class="pull-left" alt="{{$item->itemable->name}}"/>
                <a target="_blank" href="{!! $item->itemable->getUrl() !!}"
                   title="{{$item->itemable->name}}">
                    {{$item->itemable->name}}
                    {{$item->count}}
                    шт. на сумму
                    {{$item->count * $item->getPrice($order->discount)}}
                    руб.
                </a>
            </li>
            @endforeach
            @if($order->delivery_price > 0)
            <li class="list-group-item clearfix">
                    <span class="text-center col-xs-12">
                        Стоимость доставки - {!! $order->delivery_price !!} руб.
                    </span>
            </li>
            @endif
        </ul>
        <a href="{!! route('profile.orders') !!}" class="purple col-xs-12 text-center">
            <b>
                Перейти к истории заказов
            </b>
        </a>
        <div class="clearfix"></div>
        <br />
        @widget__text_widget(2)
        @widget__set(20)
        <br />
    </section>
@endsection
