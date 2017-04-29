@extends('layouts.master')

@section('content')
        <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="percent">
        </h2>

        <br>

        @if(!sizeof($sales))
            <p class="text-center">
                <i>Раздел пуст</i>
            </p>
        @else
            <div class="row items-list">
                @foreach($sales as $sale)
                    <div class="col-lg-3 col-sm-3 col-xs-6 item">
                        <a href="{!! $sale->getUrl() !!}" title="{!! $sale->name !!}">
                            <div class="photo">
                                <img src="{!! $sale->image ? create_thumbnail($sale->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=250&w=250' !!}" alt="{!! $sale->name !!}" />
                                <div class="layout">
                                    <p>
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <span>Подробнее</span>
                                    </p>
                                </div>
                            </div>
                        </a>

                        <h4 class="text-center">
                            <a href="{!! $sale->getUrl() !!}" title="{!! $sale->name !!}">
                                {{ $sale->name}}
                            </a>
                        </h4>
                        <p class="text-center">
                            <span class="price">{{  $sale->price }} руб.</span>
                        </p>
                        <p class="text-center">
                            <span class="btn-group-vertical">
                                <button class="btn btn-purple-outline" onclick="Cart.addSale('{{ $sale->id }}', '{{ csrf_token() }}')">
                                    <i class="fa fa-shopping-basket"></i> Добавить в корзину
                                </button>
                                <a class="btn btn-purple-outline" href="/api/cart/add/sale?id={{ $sale->id }}">
                                    <i class="fa fa-shopping-basket"></i> Купить в один клик
                                </a>
                            </span>
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

        <br>
    </section>

    @include('partials.florist')

@endsection