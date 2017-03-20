@extends('layouts.master')

@section('content')
    <section>
    <div class="col-sm-12">
        <a href="{!! route('pages.show' ,['slug' => $category->slug]) !!}"><i class="glyphicon glyphicon-arrow-left"></i>&nbsp;К списку</a>
    </div>
        <div class="col-sm-12">
            <h1 class="text-center">{!! $model->name !!}</h1>
            <div class="col-md-4 col-sm-12 text-center product-image-wrapper">
                @if(isset($model->image) && $model->image != "")
                    <div class="product-image">
                        <a href="{!! $model->image !!}" title="{!! $model->name !!}" data-lightbox="product">
                            <img src="{!! $model->image !!}" alt="{!! $model->name !!}">
                        </a>
                    </div>
                    @if(sizeof($model->getImages()))
                        <ul class="elastislide-list">
                            @foreach($model->getImages() as $image)
                                <li>
                                    <a href="{!! $image['link'] !!}" title="{!! $image['description'] !!}" data-lightbox="product">
                                        <img src="{!! $image['link'] !!}" alt="{!! $image['description'] !!}">
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @else
                    <div class="product-image">
                        <img src="https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=150&w=150" alt="{!! $model->name !!}">
                    </div>
                @endif
                <p class="price text-center">
                    {!! $model->price !!} руб.
                </p>
                {!! $content !!}
            </div>
            {!! $model->getContent() !!}
        </div>
        <div class="col-sm-12 product-table">
            @if(sizeof($model->getDataForTable()))
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2" class="product-description text-center">Характеристики</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($model->getDataForTable() as $key => $value)
                            <tr>
                                <td>
                                    {!! $key !!}
                                </td>
                                <td>
                                    {!! $value !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
        </div>
    </section>
    <div class="clearfix"></div>

    @widget__related_products($model->getCategoryId(), $model)

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