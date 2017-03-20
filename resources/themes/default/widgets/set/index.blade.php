<h2 class="text-center">
    Хиты продаж
    <hr class="flower">
</h2>

<br>

@if(!sizeof($sets))
    <p class="text-center">
        <i>Товаров нет</i>
    </p>
@else
    <div class="row">
        @foreach($sets as $set)
            <div class="col-md-3 col-sm-6 col-xs-6">
                <a href="{!! $set->getUrl() !!}" title="{!! $set->name !!}">
                    <div class="photo" style="background-image: url('{!! $set->image ? $set->image : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=150&w=150' !!}');">
                        <div class="layout">
                            <p>
                                Состав: {!! implode(", ", $set->flowers->pluck('title')->all()) !!}
                            </p>
                        </div>
                    </div>
                </a>

                <h4 class="text-center">
                    <a href="{!! $set->getUrl() !!}" title="{!! $set->name !!}">
                        {!! $set->name !!}
                    </a>
                </h4>
                <p class="text-center">
                    <span class="price">{!! $set->price !!} руб.</span>
                </p>
                <p class="text-center">
                    @if($set->hasInStock())
                        <span class="btn-group-vertical">
                            <button class="btn btn-purple-outline" onclick="Cart.addSet('{!! $set->id !!}', '{!! csrf_token() !!}')">
                                <i class="fa fa-shopping-basket"></i> Добавить в корзину
                            </button>
                            <a class="btn btn-purple-outline" href="/api/cart/add/set?id={!! $set->id !!}&_token={!! csrf_token() !!}">
                                <i class="fa fa-shopping-basket"></i> Купить в один клик
                            </a>
                        </span>
                    @else
                        <span class="label label-danger">Нет в наличии</span>
                    @endif
                </p>
            </div>
        @endforeach
    </div>
@endif