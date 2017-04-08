<div class="row gifts-list" data-category="init" data-active="true">
    <p class="text-muted" @if(sizeof($init_collection)) data-active="true" @else data-active="false" @endif>
        Показано <span>9</span> из {{count($init_collection)}} подарков
    </p>
    @php($counter = 1)
    @foreach($init_collection as $key => $present)
        <div class="col-md-4 col-sm-6 col-xs-6 item" data-date="{{ $present->created_at->format("Ymd") }}"
             data-price="{{$present->price}}"
             data-position="{{$key}}"
             @if($counter > 9) data-active="false" @else data-active="true" @endif
        >
            <a href="{{ $present->getUrl() }}" title="{{$present->getShowName()}}">
                <div class="photo" style="background-image: url('{{ $present->image ? create_thumbnail($present->image, 350, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=350&w=250' }}');">
                    <div class="layout">
                        <p>{{$present->getShowName()}}</p>
                    </div>
                </div>
            </a>

            <h4 class="text-center">
                <a href="{!! $present->getUrl() !!}">{{ $present->name }}</a>
            </h4>

            <p class="text-center">
                <span class="price">{{ $present->price }} руб.</span>
            </p>
                @include('single_page.partials.buy_buttons')
            <br>
        </div>
        @php($counter++)
    @endforeach
    <div class="col-xs-12 text-center">
        <a class="btn btn-purple show-more"
           @if(count($init_collection) > 9)
           data-active="true"
           @else
           data-active="false"
           @endif
        >
            Показать еще
        </a>
    </div>
</div>
