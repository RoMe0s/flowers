@extends('layouts.master')

@section('content')
    <?php

    $presents = \App\Models\Product::with(['translations', 'category'])->visible()->get();

    $real_presents = array();

    foreach($presents as $present) {

        $real_presents[] = $present;
        $real_presents[] = $present;
        $real_presents[] = $present;

    }

    $presents = $real_presents;

    $categories = array();

    foreach($presents as $present) {

        if($present->category_id) {

            $categories[$present->category_id] = $present->category->name;

        }

    }

    $categories = array_merge($categories, $categories, $categories);

    ?>
    <section class="gifts">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-center">
                    {!! $model->name !!}
                    <hr class="gift"/>
                </h1>
                <br />
                {!! $model->getContent() !!}
                <br />
            </div>
        </div>
                <div class="selector-list row">
                    <ul class="col-sm-12 col-md-12 col-xs-12">
                        @foreach($categories as $category_id => $category)
                            <li class="col-md-2 col-sm-6 col-xs-6" data-category="{{$category_id}}" data-active="false">
                                <a title="{{$category}}">
                                    {{$category}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12 filters-block">

                    <div class="col-md-6 col-sm-6 col-xs-6 filter-item">

                        {!! Form::select('price', ['asc' => 'По возрастанию', 'desc' => 'По убыванию'], null, array('class' => 'form-control input-sm', 'placeholder' => 'По цене')) !!}

                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6 filter-item">

                        {!! Form::select('date', ['asc' => 'По возрастанию', 'desc' => 'По убыванию'], null, array('class' => 'form-control input-sm', 'placeholder' => 'По дате')) !!}

                    </div>

            </div>

        </div>

        <div class="row gifts-wrapper">
            <div class="col-xs-12">
                    <p class="text-center text-danger" @if(!isset($presents) || !sizeof($presents)) data-active="true" @else data-active="false" @endif>
                        <i>По вашему запросу ничего не найдено</i>
                    </p>
                    <p class="text-muted" @if(sizeof($presents)) data-active="true" @else data-active="false" @endif>
                        По вашему запросу найдено <span>{{count($presents)}}</span> подарков
                    </p>

                    <div class="row gifts-list" data-loaded="false">
                        @foreach($presents as $key => $present)
                            <div class="col-md-4 col-sm-6 col-xs-6" data-category="{{$present->category_id}}" data-date="{!! $present->created_at->format('Ymd') !!}" data-price="{!! $present->price !!}" @if($key > 8) data-active="false" @else data-active="true" @endif>
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
                        @endforeach
                            <div class="col-xs-12 text-center">
                                <a class="btn btn-purple show-more" @if($key > 8) data-active="true" @else data-active="false" @endif>
                                    Весь список
                                </a>
                            </div>
                    </div>
            </div>
        </div>

        @widget__set(20)

    </section>
@endsection

@section('scripts')
    @parent
    <script src="{!! Theme::asset('js/presents.js') !!}"></script>
@endsection
