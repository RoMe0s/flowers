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

                    <div class="col-lg-3 col-sm-3 col-xs-12">
                        <div class="photo" style="background-image: url('{!! $sales[$i]->image !!}');">
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

                        <p class="text-center">
                            <span class="price">{{ $sales[$i]->price }} руб.</span>
                        </p>

                        <p class="text-center btn-mobile">
                            <button class="btn btn-outline" onclick="Cart.addSale('{{ $sales[$i]->id }}', '{{ csrf_token() }}')">
                                Добавить в корзину <i class="fa fa-shopping-bag"></i>
                            </button>
                            <br><br>
                            <a class="btn btn-outline" href="/api/cart/add/sale?id={{ $sales[$i]->id }}">
                                Купить в один клик <i class="fa fa-shopping-basket"></i>
                            </a>
                        </p>
                    </div>
                @endfor
            </div>
        @endif

        <br>
    </section>

    @include('partials.florist')

@endsection