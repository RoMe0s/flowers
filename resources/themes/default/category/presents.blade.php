@extends('category.index')

@section('category-content')
    <section>
        @if(!isset($products) || !sizeof($products))
            <p class="text-center">
                <i>Подарков в продаже нет</i>
            </p>
        @else

            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                    <div class="filters col-sm-12 col-md-8 text-right">
                        @widget__category_filter()
                    </div>

                    <p class="filters-count col-md-4 col-sm-12 text-muted">
                        По вашему запросу найдено {{$products->total()}} подарков
                    </p>
                </div>
            </div>

            <div class="row">
                @php($i = 0)
                @foreach($products as $product)
                    @if($i % 4 == 0 && $i != 0) {!! '</div><br><div class="row">' !!} @endif
                    <div class="col-lg-3 col-sm-3 col-xs-6">
                        <a href="{!! $product->getUrl() !!}" title="{{$product->getShowName()}}">
                            <div class="photo" style="background-image: url('{{ $product->image ? create_thumbnail($product->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=300&w=300' }}');">
                                <div class="layout">
                                    <p>
                                        {{$product->getShowName()}}
                                    </p>
                                </div>
                            </div>
                        </a>
                        <h4 class="text-center">
                            <a href="{!! $product->getUrl() !!}">{{ $product->name }}</a>
                        </h4>
                        <p class="text-center">
                            <span class="price">{{ $product->price }} руб.</span>
                        </p>
                        <p class="text-center">
                                <span class="btn-group-vertical">
                                    <button class="btn btn-purple-outline" onclick="Cart.addProduct('{{ $product->id }}', '{{ csrf_token() }}')">
                                        Добавить в корзину <i class="fa fa-shopping-basket"></i>
                                    </button>
                                    <a class="btn btn-purple-outline" href="/api/cart/add/product?id={{ $product->id }}">
                                        Купить в один клик <i class="fa fa-shopping-basket"></i>
                                    </a>
                                </span>
                        </p>
                    </div>

                    @php($i++)
                @endforeach
            </div>
            <br>
            <div class="text-center">{{ $products->appends(request()->except('page'))->links() }}</div>
        @endif


            @widget__set(20)
            <br />
    </section>

@endsection