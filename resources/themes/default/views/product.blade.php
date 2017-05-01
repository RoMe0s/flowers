@extends('layouts.master')

@section('content')
    <section>
        @include('partials.breadcrumbs')
        <div class="product-layout" itemscope itemtype="http://schema.org/Product">
            <div class="col-md-7 col-sm-12 text-center product-image-wrapper">
                    <div class="product-image">
                        <a href="{!! $model->image !!}" title="{{$model->name}}" data-lightbox="product">
                            <div class="photo">
                                <img itemprop="image" src="{!! $model->image ? create_thumbnail($model->image, 650, 600) : 'https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=550&w=500' !!}" alt="{!! $model->name !!}" />
                                <div class="layout">
                                    <p>
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        <span>смотреть</span>
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
            </div>
            <div class="col-md-5 col-sm-12 product-info">
                <h1 class="text-center" itemprop="name">{!! $model->name !!}</h1>
                <p class="price text-center" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <span itemprop="price">{!! $model->price !!}</span>
                    <meta itemprop="priceCurrency" content="RUR">руб.
                    <link itemprop="availability" href="http://schema.org/InStock" content="{!! has_in_stock_for_seo($model) !!}">
                    <meta itemprop="category" content="{!! $category->name !!}">
                </p>
                {!! $content !!}
                @if(strlen($model->getContent()) > 0)
                    <h3>
                        Описание
                    </h3>
                    <div class="description" itemprop="description">
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

    @widget__related_products($model->getCategoryId(), $model,  20)

    <section>
        @widget__set(20, $model)
    </section>

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
