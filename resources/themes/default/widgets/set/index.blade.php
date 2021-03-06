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
        {{--@php($counter = 0)--}}
        <div class="@if(!isset($static) || !$static) slick @else items-list @endif row">
            @foreach($sets as $set)
                {{--@if($counter == 0) <div class="row"> @endif--}}
                <div @if(!isset($static) || !$static) class="col-md-3 col-sm-6" @else class="col-md-4 col-sm-6 col-xs-6 item" @endif>
                    <a href="{!! $set->getUrl() !!}" title="{!! $set->name !!}">
                        <div class="photo" style="background-image: url('{!! create_thumbnail($set->image, 320, 300) !!}')">
                            <img src="{!! $set->image ? create_thumbnail($set->image, 320, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=320&w=250' !!}" alt="{{ $set->name }}" />
                            <div class="layout">
                                <p>
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    <span>Подробнее</span>
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
                    @if($set instanceof \App\Models\Product)
                        @include('partials.content.product', ['model' => $set])
                    @elseif($set instanceof \App\Models\Set)
                        @include('partials.content.set', ['model' => $set])
                    @elseif($set instanceof \App\Models\Bouquet)
                        @include('partials.content.bouquet', ['model' => $set])
                    @endif
                </div>
                {{--@php($counter++)--}}
                {{--@if($counter == 4) </div> @php($counter = 0) @endif--}}
            @endforeach
        </div>
@endif
