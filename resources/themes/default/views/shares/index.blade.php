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
            <div class="row">
                @for($i = 0; $i < $sales->count(); $i++)
                    @if($i % 4 == 0 && $i != 0) {!! '</div><br><div class="row">' !!} @endif

                    <div class="col-lg-3 col-sm-3 col-xs-6">
                            <div class="photo" style="background-image: url('{!! $sales[$i]->image ? create_thumbnail($sales[$i]->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=250&w=250' !!}');">
                                <div class="layout">
                                    <button class="btn btn-outline" onclick="Cart.addSale('{{ $sales[$i]->id }}', '{{ csrf_token() }}')">
                                        Добавить в корзину <i class="fa fa-shopping-bag"></i>
                                    </button>
                                    <br><br>
                                    <a class="btn btn-outline" href="/api/cart/add/sale?id={{ $sales[$i]->id }}">
                                        Купить в один клик <i class="fa fa-shopping-basket"></i>
                                    </a>
                                </div>
                            </div>

                        <h4 class="text-center">
                            <a href="{!! $sales[$i]->getUrl() !!}" title="{!! $sales[$i]->name !!}">
                                {{ $sales[$i]->name}}
                            </a>
                        </h4>
                        <p class="text-center">
                            <span class="price">{{  $sales[$i]->price }} руб.</span>
                        </p>
                        <p class="text-center btn-mobile">
                            <span class="btn-group-vertical">
                                <button class="btn btn-purple-outline" onclick="Cart.addSale('{{ $sales[$i]->id }}', '{{ csrf_token() }}')">
                                    <i class="fa fa-shopping-basket"></i> Добавить в корзину
                                </button>
                                <a class="btn btn-purple-outline" href="/api/cart/add/sale?id={{ $sales[$i]->id }}">
                                    <i class="fa fa-shopping-basket"></i> Купить в один клик
                                </a>
                            </span>
                        </p>
                    </div>
                @endfor
            </div>
        @endif

        <br>
    </section>

    @include('partials.florist')

@endsection