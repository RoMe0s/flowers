@extends('layouts.master')

@section('content')

    <h1 class="text-center">
        {!! $model->name !!}
        <hr class="box">
    </h1>
    <br />
    {!! $model->short_content !!}
    <br/>

    <section>
        @if(sizeof($model->visible_children))
            <div class="row">
             @foreach($model->visible_children as $key => $visible_child)
                     <div class="col-sm-4 col-xs-6">
                         <a href="{!! $visible_child->getUrl() !!}">
                             <div class="photo" style="background-image: url('{!! $visible_child->image ? create_thumbnail($visible_child->image, 350,  250) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=350&w=250" !!}');">
                                 <div class="title">{!! $visible_child->name !!}</div>
                             </div>
                         </a>
                     </div>
                @endforeach
            </div>
        @endif
        @include('partials.breadcrumbs')
    </section>
    @section('category-content')

        <section>

            @widget__text_widget(6)

            @if($model->id == 1)
                @widget__text_widget(9)
            @elseif($model->id == 3)
                @widget__text_widget(10)
            @endif

            <div class="row">
                <div class="col-xs-12">
                    @if(!isset($sets) || !sizeof($sets))
                        <p class="text-center text-danger">
                            <i>По вашему запросу ничего не найдено</i>
                        </p>
                    @else

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xs-12">
                                <div class="filters col-sm-12 col-md-8 text-right">
                                    @widget__category_filter()
                                </div>

                                <p class="filters-count col-md-4 col-sm-12 text-muted">
                                    По вашему запросу найдено {{$sets->total()}} наборов
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($sets as $set)
                                <div class="col-md-4 col-sm-6 col-xs-6">
                                    <a href="{{ $set->getUrl() }}" title="{!! $set->name !!}">
                                        <div class="photo" style="background-image: url('{{ $set->image ? create_thumbnail($set->image, 350, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=350&w=250' }}');">
                                            <div class="layout">
                                                <p>Состав: {{ implode(", ", $set->flowers->pluck('title')->all()) }}</p>
                                            </div>
                                        </div>
                                    </a>

                                    <h4 class="text-center">
                                        <a href="{!! $set->getUrl() !!}">{{ $set->box->title }}<br>{{ $set->box->size() }} см.</a>
                                    </h4>

                                    <p class="text-center">
                                        <span class="price">{{ $set->price }} руб.</span>
                                    </p>
                                    <p class="text-center">
                                        @if($set->hasInStock())
                                            <span class="btn-group-vertical">
                                                <button class="btn btn-purple-outline" onclick="Cart.addSet('{{ $set->id }}', '{{ csrf_token() }}')">
                                                    <i class="fa fa-shopping-basket"></i> Добавить в корзину
                                                </button>
                                                <a class="btn btn-purple-outline" href="/api/cart/add/set?id={{ $set->id }}">
                                                    <i class="fa fa-shopping-basket"></i> Купить в один клик
                                                </a>
                                            </span>
                                        @else
                                            <span class="label label-danger">Нет в наличии</span>
                                        @endif
                                    </p>
                                    <br>
                                </div>
                            @endforeach
                        </div>
                        <br>
                        <div class="text-center">{{ $sets->appends(request()->except('page'))->links() }}</div>
                    @endif
                </div>
            </div>
        </section>

    @show

    @widget__related_products($model->id)

    @include('partials.individual-set')

    @widget__text_widget(4)
    <br />

    {!! $model->content !!}

    @include('partials.florist')
    <br/>

    {!! $model->content_two !!}
@endsection