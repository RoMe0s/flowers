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
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="photo" style="background-image: url('{!! $set->image !!}');">
                    <div class="layout">
                        <p>
                            Состав: {!! implode(", ", $set->flowers->pluck('title')->all()) !!}
                        </p>
                    </div>
                </div>

                <br>

                <p class="text-center">{!! $set->box->category->title !!}</p>
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