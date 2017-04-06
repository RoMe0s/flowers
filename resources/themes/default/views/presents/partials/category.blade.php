<div class="row gifts-list" data-category="{{$category->id}}" @if(!isset($category_number) ||$category_number == 0) data-active="true" @else data-active="false" @endif>
    <p class="text-center text-danger" @if(!sizeof($category->visible_directProducts)) data-active="true" @else data-active="false" @endif>
        <i>По вашему запросу ничего не найдено</i>
    </p>
    <p class="text-muted" @if(sizeof($category->visible_directProducts)) data-active="true" @else data-active="false" @endif>
        Показано {!! $page * 9 <= count($category->visible_directProducts) ? $page * 9 : count($category->visible_directProducts)!!} из {{count($category->visible_directProducts)}} подарков
    </p>
    @php($counter = 1)
    @foreach($category->visible_directProducts as $present)
        <div class="col-md-4 col-sm-6 col-xs-6">
            <a href="{{ $present->getUrl() }}" title="{{ $present->name }} {{ (!empty($present->size))? '('.$present->size.')': '' }}">
                <div class="photo" style="background-image: url('{{ $present->image ? create_thumbnail($present->image, 350, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=350&w=250' }}');">
                    <div class="layout">
                        <p>{{ $present->name }} {{ (!empty($present->size))? '('.$present->size.')': '' }}</p>
                    </div>
                </div>
            </a>

            <h4 class="text-center">
                <a href="{!! $present->getUrl() !!}">{{ $present->name }}</a>
            </h4>

            <p class="text-center">
                <span class="price">{{ $present->price }} руб.</span>
            </p>
            <p class="text-center">
                                        <span class="btn-group-vertical">
                                            <button class="btn btn-purple-outline" onclick="Cart.addProduct('{{ $present->id }}', '{{ csrf_token() }}')">
                                                <i class="fa fa-shopping-basket"></i> Добавить в корзину
                                            </button>
                                            <a class="btn btn-purple-outline" href="/api/cart/add/product?id={{ $present->id }}">
                                                <i class="fa fa-shopping-basket"></i> Купить в один клик
                                            </a>
                                        </span>
            </p>
            <br>
        </div>
        @if($counter >= $page * 9)
            @break
        @endif
        @php($counter++)
    @endforeach

    @if(count($category->visible_directProducts) > 9)
        <div class="col-xs-12 text-center">
            <a class="btn btn-purple show-more"
               data-page="{{$page}}"
               @if($counter >= count($category->visible_directProducts)) data-type="less"> Скрыть @else data-type="more" > Показать еще @endif
            </a>
        </div>
    @endif
</div>
