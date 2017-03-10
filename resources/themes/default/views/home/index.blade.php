@extends('layouts.master')

@section('content')
    <section>

        @widget__category()

        @widget__set()

    </section>
    <br />

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