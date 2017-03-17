<p class="text-center">
    @if($model->hasInStock())
        <span class="btn-group-vertical">
                            <button class="btn btn-purple-outline" onclick="Cart.addBouquet('{!! $model->id !!}', '{!! csrf_token() !!}')">
                                <i class="fa fa-shopping-basket"></i> Добавить в корзину
                            </button>
                            <a class="btn btn-purple-outline" href="/api/cart/add/bouquet?id={!! $model->id !!}&_token={!! csrf_token() !!}">
                                <i class="fa fa-shopping-basket"></i> Купить в один клик
                            </a>
                        </span>
    @else
        <span class="label label-danger">Нет в наличии</span>
    @endif
</p>