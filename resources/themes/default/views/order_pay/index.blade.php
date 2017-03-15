@extends('layouts.master')

@section('content')

    {!! $model->content !!}

    <section>
        @if($order->status == 0)
            <h1 class="text-center">ЗАКАЗ #{{ $order->id }} ОТМЕНЁН</h1>

            <p class="text-center">
                Вы не можете оплатить отменённый заказ
            </p>
            <p class="text-center">
                <a class="btn btn-default" href="/profile/orders">
                    Вернуться к заказам
                </a>
            </p>
        @elseif($order->status > 2)
            <h1 class="text-center">ЗАКАЗ #{{ $order->id }} УЖЕ ОПЛАЧЕН</h1>

            <p class="text-center">
                Вы не можете оплатить уже оплаченный заказ
            </p>
            <p class="text-center">
                <a class="btn btn-default" href="/profile/orders">
                    Вернуться к заказам
                </a>
            </p>
        @else
            <h2 class="text-center">
                ВАШ ЗАКАЗ #{{ $order->id }}
                <hr class="doc">
            </h2>

            <br>

            <div class="row">
                <div class="col-lg-8 col-sm-8 col-xs-12">
                    <table class="table">
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ isset($item->itemable->name) ? $item->itemable->name : $item->itemable->title }}</td>
                                <td>x{{ $item->count }}</td>
                                <td>{{ $item->getPrice() }} руб</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-lg-4 col-sm-4 col-xs-12" style="background: #f0f0f0;">
                    <table class="table">
                        <tr>
                            <td>Цена без скидки</td>
                            <td>{{ $order->getTotalWithoutDiscount() }} руб</td>
                        </tr>
                        <tr>
                            <td>Скидка</td>
                            <td>{{ $order->getTotalWithoutDiscount()  - $order->getTotal() }} руб ({{ $order->discount }}%)</td>
                        </tr>
                        <tr>
                            <td>Цена со скидкой</td>
                            <td>{{ $order->getTotal() }} руб</td>
                        </tr>
                        <tr>
                            <td>Цена доставки</td>
                            <td>{{ $order->delivery_price }} руб</td>
                        </tr>
                        <tr>
                            <td>Предоплата</td>
                            <td>{{ $order->prepay }}%</td>
                        </tr>
                        <tr>
                            <td>
                                <b>К ОПЛАТЕ</b>
                            </td>
                            <td>
                                <b>{{ $order->totalPrepay() }} руб</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <br>

            <form action="https://money.yandex.ru/eshop.xml" method="post">
                <input type="hidden" name="shopId" value="{{ env('SHOP_ID') }}">
                <input type="hidden" name="scid" value="{{ env('SC_ID') }}">
                <input type="hidden" name="sum" value="{{ $order->totalPrepay() }}">
                <input type="hidden" name="orderNumber" value="{{ $order->id }}">
                <input type="hidden" name="cps_email" value="{{ $order->user->email }}">

                <p class="text-center">
                    <a class="btn btn-default btn-lg" href="/order/{{ $order->id }}/cancel">Отменить</a>
                    <input class="btn btn-purple btn-lg" type="submit" value="Оплатить" onclick="yaCounter29938144.reachGoal('pay'); return true;">
                </p>
            </form>
        @endif
    </section>
@endsection