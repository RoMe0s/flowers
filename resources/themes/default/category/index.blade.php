@extends('layouts.master')

@section('content')


    @section('category-content')

        <section>

            <h1 class="text-center">
                {!! $model->name !!}
                <hr class="box">
            </h1>
            <br />
            {!! $model->short_content !!}
            <br/>

            @widget__text_widget(6)

            <div class="row">
                <div class="col-lg-3 col-sm-4 col-xs-12">
                    @widget__category_filter()
                </div>
                <div class="col-lg-9 col-sm-8 col-xs-12">
                    @if(!isset($sets) || !sizeof($sets))
                        <p class="text-center text-danger">
                            <i>По вашему запросу ничего не найдено</i>
                        </p>
                    @else
                        <p class="text-right text-muted">
                            По вашему запросу найдено {{ count($sets) }} наборов
                        </p>

                        <div class="row">
                            @foreach($sets as $set)
                                <div class="col-md-4 col-sm-6 col-xs-6">
                                    <a href="{{ $set->getUrl() }}" title="{!! $set->name !!}">
                                        <div class="photo" style="background-image: url('{{ $set->image ? create_thumbnail($set->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=250&w=250' }}');">
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