@if(!sizeof($list->where('menuable_type', (string)\App\Models\Category::class)))
    <p class="text-center">
        <i>Товаров нет</i>
    </p>
@else
    <div class="mainpage_menu categories">
        @foreach($list->where('menuable_type', (string)\App\Models\Category::class) as $category)
            @php($category = $category->data)
            <div class="category">
                <h2 class="text-center">
                    {{$category->name}}
                    <hr class="box" />
                </h2>
                <div class="row items-list">
                    @foreach($category->products as $set)
                        {{--@if($counter == 0) <div class="row"> @endif--}}
                        <div class="col-sm-6 col-xs-6 item">
                            <a href="{!! $set->getUrl() !!}" title="{!! $set->name !!}">
                                <div class="photo">
                                    <img src="{!! $set->image ? create_thumbnail($set->image, 370, 250) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=370&w=250' !!}" alt="{{ $set->name }}" />
                                    <div class="layout">
                                        <p>
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <span>Подробнее</span>
                                        </p>
                                    </div>
                                </div>
                            </a>

                            <h4 class="text-center">
                                <a href="{!! $set->getUrl() !!}" title="{!! $set->getShowName() !!}">
                                    {{ $set->getShowName() }}
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
                    @endforeach
                    <div class="clearfix"></div>
                    <div class="col-sm-12 text-center">
                        <br />
                        <a class="btn btn-purple form-control" href="{!! $category->getUrl() !!}" title="{!! $category->name !!}">Перейти в категорию</a>
                    </div>
                </div>
            </div>
    @endforeach
    </div>
@endif