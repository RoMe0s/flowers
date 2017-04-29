@extends('layouts.master')

@section('content')
    <section class="gifts">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-center">
                    {!! $model->name !!}
                    <hr class="gift"/>
                </h1>
            </div>
        </div>
                <div class="selector-list row">
                    <ul class="col-sm-12 col-md-12 col-xs-12">
                        @foreach($categories as $category)
                            <li class="col-md-3 col-sm-6 col-xs-6"
                                data-category="{{$category->id}}"
                                data-active="false"
                                {{--style="background-image: url('{!! $category->image ? create_thumbnail($category->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=250&w=250' !!}')"--}}
                            >
                                <img src="{!! $category->image ? create_thumbnail($category->image, 300, 300) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=250&w=250' !!}" alt="{!! $category->name !!}" />
                                <a href="{!! $category->getUrl() !!}" title="{{$category->name}}">
                                    <p>
                                        {{$category->name}}
                                    </p>
                                </a>
                                <a class="show-category">
                                    <p>
                                        Показать
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
        <div class="row">

            <div class="col-xs-12 mobile-padding">
                @widget__global_price_filter($model->slug)
            </div>

            <div class="col-sm-12 col-xs-12 col-md-12 filters-block">

                    <div class="col-md-6 col-sm-6 col-xs-6 filter-item">

                        {!! Form::select('price', ['asc' => 'По возрастанию', 'desc' => 'По убыванию'], null, array('class' => 'form-control input-sm', 'placeholder' => 'По цене')) !!}

                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6 filter-item">

                        {!! Form::select('date', ['asc' => 'По возрастанию', 'desc' => 'По убыванию'], null, array('class' => 'form-control input-sm', 'placeholder' => 'По дате')) !!}

                    </div>

            </div>

        </div>

        <div class="col-sm-12 gifts-wrapper" data-loaded="false">
            @include('single_page.partials.init')
            @if(sizeof($categories))
                @foreach($categories as $category_number => $category)
                   @include('single_page.partials.category')
                @endforeach
            @else
                <p class="text-center text-danger">
                    <i>По вашему запросу ничего не найдено</i>
                </p>
            @endif
            <br />
        </div>

        @if(isset($page_type) && $page_type === 'presents')
            @widget__set(20)
        @else
            @widget__related_products(-1, null, 20)
        @endif

        <br />
        {!! $model->getContent() !!}
        <br />

    </section>
@endsection

@section('scripts')
    @parent
    <script src="{!! Theme::asset('js/presents.js') !!}"></script>
@endsection
