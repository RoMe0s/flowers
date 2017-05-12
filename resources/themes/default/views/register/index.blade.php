@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="login">
        </h2>

        {!! $model->content !!}

        <div class="row">
            <div class="col-sm-6 col-xs-12">
                @widget__text_widget(8)
            </div>
            <div class="col-sm-6 col-xs-12">
                <form action="{!! route('post.reg') !!}" method="post">
                    {!! csrf_field() !!}

                    @include('errors.form')

                    <div class="row">
                        <div class="col-xs-12">
                            <p>
                                Номер телефона
                                <input class="form-control input-sm" type="tel" name="phone" value="{{ old('phone') }}" required>
                            </p>
                            @php($old = old('email') !== null && !empty(old('email')) || old('name') !== null && !empty(old('name')))
                            @php($oauth = isset($_GET['email']) || isset($_GET['name']) ? true : false)
                            <div class="additional-inputs" data-active="@if($old || $oauth){{----}}true{{----}}@else{{----}}false{{----}}@endif">
                                <p class="button">
                                    <a class="collapse-button" href="#">
                                        <i class="fa fa-caret-right" aria-hidden="true"></i>
                                        <span>Дополнительные поля</span>
                                    </a>
                                </p>
                                <p>
                                    ФИО
                                    <input @if($old || $oauth) name="name" @endif class="form-control input-sm" type="text" data-name="name" value="{{ (isset($_GET['name'])? $_GET['name']: old('name')) }}">
                                </p>
                                <p>
                                    Email
                                    <input @if($old || $oauth) name="email" @endif class="form-control input-sm" type="email" data-name="email" value="{{ (isset($_GET['email'])? $_GET['email']: old('email')) }}">
                                </p>
                            </div>
                        </div>
                    </div>

                    <p>
                        <input type="checkbox" name="agreement" value="1" required @if( old('agreement') ) checked @endif> Я ознакомился(-лась) и согласен(-сна) с <a href="{{ url('/offer') }}" target="_blank">условиями Публичной ОФЕРТЫ</a>.
                    </p>
                    <p class="text-center">
                        <input class="btn btn-default" type="submit" value="Отправить">
                    </p>
                </form>
            </div>
        </div>
    </section>
@endsection


@section('styles')
    @parent
    <style>
        .title {
            font-size: 18px;
            margin-bottom: 40px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="{!! Theme::asset('js/auth.js') !!}"></script>
@endsection
