<div class="row gifts-list" data-category="{{$category->id}}" @if(isset($show) && $show === true) data-active="true" @else data-active="false" @endif>
    <p class="text-center text-danger" @if(!sizeof($category->products)) data-active="true" @else data-active="false" @endif>
        <i>По вашему запросу ничего не найдено</i>
    </p>
    <p class="text-muted" @if(sizeof($category->products)) data-active="true" @else data-active="false" @endif>
        Показано {!! $page * 9 <= count($category->products) ? $page * 9 : count($category->products)!!} из {{count($category->products)}} подарков
    </p>
    @php($counter = 1)
    @foreach($category->products as $present)
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
            @include('presents.partials.buy_buttons')
            <br>
        </div>
        @if($counter >= $page * 9)
            @break
        @endif
        @php($counter++)
    @endforeach
        <div class="col-xs-12 text-center">
            <a class="btn btn-purple show-more"
               @if(count($category->products) > 9)
               data-active="true"
               @else
               data-active="false"
               @endif
               data-page="{{$page}}"
               @if(isset($type) && $type == "less") data-type="less"> Скрыть @else data-type="more" > Показать еще @endif
            </a>
        </div>
</div>
