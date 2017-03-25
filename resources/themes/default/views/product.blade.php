@extends('layouts.master')

@section('content')
    <section>
    <div class="col-sm-12 breadcrumbs">
        <a title="{{config('app.name')}}" href="{!! route('home') !!}">Главная</a>&nbsp;/&nbsp;
        <a title="{{$category->name}}" href="{!! route('pages.show' ,['slug' => $category->slug]) !!}">{{$category->name}}</a>
    </div>
        <div class="product-layout">
            <div class="col-md-7 col-sm-12 text-center product-image-wrapper">
                @if(isset($model->image) && $model->image != "")
                    <div class="product-image">
                        <a href="{!! $model->image !!}" title="{{$model->name}}" data-lightbox="product">
                            <div class="photo" style="background-image: url('{{ $model->image ? create_thumbnail($model->image, 650, 600) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=550&w=500' }}');">
                                <div class="layout">
                                    <p>
                                        {{$model->name}}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @if(sizeof($model->getImages()))
                        <ul class="elastislide-list">
                            @foreach($model->getImages() as $image)
                                <li>
                                    <a href="{!! $image['link'] !!}" title="{!! $image['description'] !!}" data-lightbox="product">
                                        <img src="{!! $image['link'] ? create_thumbnail($image['link'], 100) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=100&w=100" !!}" alt="{!! $image['description'] !!}">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            </div>
            <div class="col-md-5 col-sm-12 product-info">
                <h1 class="text-center">{!! $model->name !!}</h1>
                <p class="price text-center">
                    {!! $model->price !!} руб.
                </p>
                {!! $content !!}
                @if(strlen($model->getContent()) > 0)
                    <h3>
                        Описание
                    </h3>
                    <div class="description">
                        {!! $model->getContent() !!}
                    </div>
                @endif

                <div class="product-table">
                    @if(sizeof($model->getDataForTable()))
                        <table class="table">
                            <tbody>
                                @foreach($model->getDataForTable() as $key => $value)
                                    <tr>
                                        <td>
                                            <b>{!! $key !!}</b>:
                                            {!! $value !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
    </section>
    <div class="clearfix"></div>

    @widget__related_products($model->getCategoryId(), $model,  4)

    <section>
        @widget__set(4, $model)
    </section>
    <br />

@endsection

@section('styles')
    @parent
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/components/elastislide/css/elastislide.css') !!}">

@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{!! asset('assets/components/elastislide/js/jquery.elastislide.js') !!}"></script>
    <script>
        $(document).ready(function() {
            $('.elastislide-list').elastislide({
                orientation : 'horizontal',
                minItems : 3,
                speed : 500,
                easing : 'ease-in-out'
            });
        });
    </script>
@endsection