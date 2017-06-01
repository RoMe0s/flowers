@extends('profile.index')

@section('profile-content')

    @if(!sizeof($orders))
        <p class="text-center">
            <i>Заказов нет</i>
        </p>
    @else
        @foreach($orders as $order)
            <h4>Заказ #{{ $order->id }} - <span class="{{ ($order->status == 0) ? 'text-danger': 'text-success' }}">{!! $order->getStringStatus() !!}</span></h4>

            <table class="table">
                <tr>
                    <td>Предоплата</td>
                    <td>{{ $order->totalPrepay() }} руб. ({{ $order->prepay }}%)</td>
                </tr>
                <tr>
                    <td>Стоимость заказа</td>
                    <td>{{ $order->getTotal() }} руб.</td>
                </tr>
                @if(!empty($order->recipient_name) || !empty($order->recipient_phone))
                <tr>
                    <td>
                        Получатель
                    </td>
                    <td>
                        {{$order->recipient_name . ' ' . $order->recipient_phone}}
                    </td>
                </tr>
                @endif
                @if($order->neighbourhood)
                    <tr>
                        <td>
                            Оставить соседям в случае отсутствия получателя
                        </td>
                        <td>
                            Да
                        </td>
                    </tr>
                @endif
                @if($order->getAddress() != "")
                <tr>
                    <td>Адрес доставки</td>
                    <td>{{ $order->getAddress() }}</td>
                </tr>
                @endif
                @if(!empty($order->date) || !empty($order->time))
                <tr>
                    <td>
                        Дата и время доставки
                    </td>
                    <td>
                        {{ $order->date }}
                        @if($order->time)
                            {{ \App\Models\Order::getTimes()[$order->time] }}
                        @endif
                    </td>
                </tr>
                @endif
                <tr>
                    <td>
                        Дата создания заказа
                    </td>
                    <td>
                        {{ $order->created_at->format("d-m-Y H:i") }}
                    </td>
                </tr>
            </table>

            <h4 class="text-center">
                Список товаров
            </h4>
            <div class="row">
                @foreach($order->items as $item)
                    @php($item = $item->itemable)
                    @if(!$item)
                        @continue
                    @endif
                    <div class="col-lg-3 col-sm-3 col-xs-12">
                        <a href="{{ $item->image }}" data-lightbox="order_{{ $order->id }}">
                            <div class="photo photo-small" style="background-image: url('{!! thumb($item->image, 150) !!}');">
                                <div class="layout">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            @if(!empty($order->images()))
                <h4>Фото заказа</h4>

                <div class="row">
                    @foreach($order->images() as $image)
                        <div class="col-lg-3 col-sm-3 col-xs-12">
                            <a href="{{ $image }}" data-lightbox="order{{ $order->id }}">
                                <div class="photo photo-small" style="background-image: url('{!! $image ? create_thumbnail($image, 150, 150) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=150&w=150" !!}');">
                                    <div class="layout">
                                        <i class="fa fa-search"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($order->status == 2)
                <p class="text-center">
                    <a class="btn btn-purple-outline btn-lg" href="/order/{{ $order->id }}/pay">Оплатить</a>
                </p>
            @endif

            <br>
        @endforeach
    @endif
@endsection