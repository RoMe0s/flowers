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

                    <div class="col-sm-3 col-xs-6">
                        <a href="{!! $products[$i]->getUrl() !!}" title=" {{ $products[$i]->name }} {{ (!empty($products[$i]->size))? '('.$products[$i]->size.')': '' }}">
                            <div class="photo">
                                <img src="{!! $products[$i]->image ? create_thumbnail($products[$i]->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=250&w=250' !!}" alt="{!! $products[$i]->name !!}" />
                                <div class="layout">
                                    <p>
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <span>Подробнее</span>
                                    </p>
                                </div>
                            </div>
                        </a>

                        <h4 class="text-center">
                            <a href="{!! $products[$i]->getUrl() !!}" title="{!! $products[$i]->name !!}">
                                {{ $products[$i]->name }} {{ (!empty($products[$i]->size))? '('.$products[$i]->size.')': '' }}
                            </a>
                        </h4>
                        <p class="text-center">
                            <span class="price">
                                {{ $products[$i]->price }} руб.
                            </span>
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