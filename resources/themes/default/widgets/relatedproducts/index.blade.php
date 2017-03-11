@if(sizeof($products))
    <br />
    <section>

        @widget__text_widget(7)

        <div class="row">
            @foreach($products as $product)
                <div class="col-sm-3 col-xs-12">
                    <a href="{{ $product->image }}" data-lightbox="products">
                        <div class="photo" style="background-image: url('{{ $product->image }}');">
                            <div class="layout">
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                    </a>

                    <p class="text-center">
                        {{ $product->name }} {{ (!empty($product->size))? '('.$product->size.')': '' }}
                    </p>
                    <p class="text-center">
                        <span class="price">{{ $product->price }} руб.</span>
                    </p>
                    <p class="text-center">
                        <button class="btn btn-purple-outline" onclick="Cart.addProduct('{{ $product->id }}', '{{ csrf_token() }}')">
                            <i class="fa fa-shopping-basket"></i> Добавить в корзину
                        </button>
                    </p>
                </div>
            @endforeach
        </div>
    </section>
    <br />
@endif