@extends('layouts.master')

@section('content')
    <section class="main-section">

        <h1 class="text-right main-h1">Доставка цветов по Москве</h1>

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

@section('scripts')
    @parent
    <script src="{!! asset('assets/components/sticky-kit/jquery.sticky-kit.min.js') !!}"></script>
    <script src="{!! Theme::asset('js/sticky.js') !!}"></script>

    <script>
/*        function openNav() {
            document.getElementById("mobile-categories").style.width = "250px";
        }

        function closeNav() {
            document.getElementById("mobile-categories").style.width = "0";
        }

        $('.mobile-button').stick_in_parent({offset_top: 57});*/

        function collapseCategoriesList() {

            var $categories_list = $('.mainpage_menu').find('.sidebar');

            if(!$categories_list.is(':visible')) {

                $categories_list.show("slow");

            } else {

                $categories_list.hide("slow");

            }

        }
    </script>

@endsection