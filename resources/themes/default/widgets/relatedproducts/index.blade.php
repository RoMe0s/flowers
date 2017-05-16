@if(sizeof($products))
    <section>

        @widget__text_widget(7)

            <div class="slick row">
            {{--@php($counter = 0)--}}
            @foreach($products as $product)
                {{--@if($counter == 0) <div class="row"> @endif--}}
                <div class="col-sm-3 col-xs-6 related-product-item">
                    <a href="{!! $product->getUrl() !!}" title="{{ $product->name }} {{ (!empty($product->size))? '('.$product->size.')': '' }}">
                        <div class="photo" style="background-image: url('{!! create_thumbnail($product->image, 300, 300) !!}')">
                            <img src="{{ $product->image ? create_thumbnail($product->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=150&w=150' }}" title="{{ $product->name }}" />
                            <div class="layout">
                                <p>
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    <span>Подробнее</span>
                                </p>
                            </div>
                        </div>
                    </a>

                    <h4 class="text-center">
                        <a href="{!! $product->getUrl() !!}" title="{{ $product->name }} {{ (!empty($product->size))? '('.$product->size.')': '' }}">
                            {{ $product->name }} {{ (!empty($product->size))? '('.$product->size.')': '' }}
                        </a>
                    </h4>
                    <p class="text-center">
                        <span class="price">{{ $product->price }} руб.</span>
                    </p>
                    <p class="text-center">
                        <span class="btn-group-vertical">
                            <button class="btn btn-purple-outline" onclick="Cart.addProduct('{{ $product->id }}', '{{ csrf_token() }}')">
                                <i class="fa fa-shopping-basket"></i> Добавить в корзину
                            </button>
                        </span>
                    </p>
                </div>
                {{--@php($counter++)--}}
                {{--@if($counter == 4) </div> @php($counter = 0) @endif--}}
            @endforeach
            </div>
    </section>
@endif