@extends('layouts.master')

@section('content')

    <section class="main-section">

        <h1 class="text-right main-h1">Доставка цветов по Москве</h1>

        @widget__banner('mainpage')

        {{--@widget__category()--}}

        @widget__mainpage_menu()

        @widget__hits()
        <br />

    </section>

    <div class="bg">
        <section>
            @widget__text_widget(2)
        </section>
    </div>

    @widget__news()

    <div class="image">
        <section>

            @widget__text_widget(1)

            <p class="text-center">
                <a class="btn btn-outline" href="{!! route('subscriptions') !!}">Подробнее</a>
            </p>
        </section>
    </div>

    <br>

    {!! $model->content !!}

@endsection

@section('popups')
    @if(session()->has('register_status') && session('register_status') === "success")
        @include('popups.register')
    @endif
@endsection

@section('scripts')
    @parent
    <script src="{!! asset('assets/components/sticky-kit/jquery.sticky-kit.min.js') !!}"></script>
    <script src="{!! Theme::asset('js/sticky.js') !!}"></script>

    <script>

        function collapseCategoriesList() {

            var $categories_list = $('.mainpage_menu').find('.sidebar');

            if(!$categories_list.is(':visible')) {

                $categories_list.fadeIn("slow");

            } else {

                $categories_list.fadeOut("slow");

            }

        }

    </script>
    @if(session()->has('register_status') && session('register_status') === "success")
        <script src="{!! Theme::asset('js/auth.js') !!}"></script>
    @endif
@endsection
