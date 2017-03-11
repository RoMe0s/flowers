@extends('layouts.master')

@section('content')
    <section>
        <h1 class="text-center">
            {!! $model->name !!}
            <hr class="bag">
        </h1>

        <br>

        @if(!sizeof($products))
            <p class="text-center">
                <i>Сопуствующих товаров нет</i>
            </p>
        @else
            <div class="row">
                @for($i = 0; $i < count($products); $i++)
                    @if($i % 4 == 0 && $i != 0) {!! '</div><br><div class="row">' !!} @endif

                    <div class="col-sm-3 col-xs-12">
                        <a href="{{ $products[$i]->image }}" data-lightbox="products">
                            <div class="photo" style="background: url('{{ $products[$i]->image }}');">
                                <div class="layout">
                                    <i class="fa fa-search"></i>
                                </div>
                            </div>
                        </a>

                        <p class="text-center">
                            {{ $products[$i]->name }} {{ (!empty($products[$i]->size))? '('.$products[$i]->size.')': '' }}
                        </p>
                        <p class="text-center">
                            <span class="price">{{ $products[$i]->price }} руб.</span>
                        </p>
                        <p class="text-center">
                            <span class="btn-group-vertical">
                                <button class="btn btn-purple-outline" onclick="Cart.addProduct('{{ $products[$i]->id }}', '{{ csrf_token() }}')">
                                    <i class="fa fa-shopping-basket"></i> Добавить в корзину
                                </button>
                                <a class="btn btn-purple-outline" href="/api/cart/add/product?id={{ $products[$i]->id }}">
                                    <i class="fa fa-shopping-basket"></i> Купить в один клик
                                </a>
                            </span>
                        </p>
                    </div>
                @endfor
            </div>

            <br>

            <div class="text-center">{{ $products->links() }}</div>
        @endif
    </section>
@endsection