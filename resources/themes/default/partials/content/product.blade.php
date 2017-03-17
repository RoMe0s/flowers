<p class="text-center">
        <span class="btn-group-vertical">
                            <button class="btn btn-purple-outline" onclick="Cart.addProduct('{!! $model->id !!}', '{!! csrf_token() !!}')">
                                <i class="fa fa-shopping-basket"></i> Добавить в корзину
                            </button>
                            <a class="btn btn-purple-outline" href="/api/cart/add/product?id={!! $model->id !!}&_token={!! csrf_token() !!}">
                                <i class="fa fa-shopping-basket"></i> Купить в один клик
                            </a>
        </span>
</p>