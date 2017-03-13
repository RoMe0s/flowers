@extends('layouts.master')

@section('content')


    @section('category-content')

        <section>

            <h1 class="text-center">
                {!! $model->name !!}
                <hr class="box">
            </h1>

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
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    <a href="{{ $set->image }}" title="Нажмите чтобы увеличить" data-lightbox="sets">
                                        <div class="photo" style="background-image: url('{{ $set->image }}');">
                                            <div class="layout">
                                                <p>Состав: {{ implode(", ", $set->flowers->pluck('title')->all()) }}</p>
                                            </div>
                                        </div>
                                    </a>

                                    <h4 class="text-center">
                                        {{ $set->box->title }}<br>{{ $set->box->size() }} см.
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

    @widget__text_widget(4)

    @include('partials.individual-set')

    {!! $model->content !!}

    @include('partials.florist')

    {!! $model->content_two !!}
@endsection