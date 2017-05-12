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
                @if(isset($order->address))
                    <tr>
                        <td>Адрес доставки</td>
                        <td>{{ $order->getAddress() }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Стоимость заказа</td>
                    <td>{{ $order->getTotal() }} руб.</td>
                </tr>
                <tr>
                    <td>Предоплата</td>
                    <td>{{ $order->totalPrepay() }} руб. ({{ $order->prepay }}%)</td>
                </tr>
            </table>

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