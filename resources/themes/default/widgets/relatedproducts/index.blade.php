@if(sizeof($products))
    <br />
    <section>

        @widget__text_widget(7)

        <div class="row">
            @foreach($products as $product)
                <div class="col-sm-3 col-xs-6">
                    <a href="{!! $product->getUrl() !!}" title="{!! $product->name !!}">
                        <div class="photo" style="background-image: url('{{ $product->image ? $product->image : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=150&w=150' }}');">
                            <div class="layout">
                                {{ $product->name }} {{ (!empty($product->size))? '('.$product->size.')': '' }}
                            </div>
                        </div>
                    </a>

                    <h4 class="text-center">
                        <a href="{!! $product->getUrl() !!}">
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
            @endforeach
        </div>
    </section>
    <br />
@endif