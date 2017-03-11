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
                        <div class="col-md-6 col-sm-12">
                            <p>
                                ФИО
                                <input class="form-control input-sm" type="text" name="name" value="{{ (isset($_GET['name'])? $_GET['name']: old('name')) }}" required>
                            </p>
                            <p>
                                Email
                                <input class="form-control input-sm" type="email" name="email" value="{{ (isset($_GET['email'])? $_GET['email']: old('email')) }}" required>
                            </p>
                            <p>
                                Номер телефона
                                <input class="form-control input-sm" type="tel" name="phone" value="{{ old('phone') }}" required>
                            </p>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <p>
                                Пароль
                                <input class="form-control input-sm" type="password" name="password" required>
                            </p>
                            <p>
                                Повторите пароль
                                <input class="form-control input-sm" type="password" name="password_confirmation" required>
                            </p>
                        </div>
                    </div>

                    <p>
                        <input type="checkbox" name="agreement" value="1" required> Я ознакомился(-лась) и согласен(-сна) с <a href="{{ url('/offer') }}" target="_blank">условиями Публичной ОФЕРТЫ</a>.
                    </p>
                    <p class="text-center">
                        <input class="btn btn-default" type="submit" value="Зарегистрироваться">
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
    <script type="text/javascript" src="{{ asset('assets/themes/admin/vendor/adminlte/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('input[name=phone]').inputmask({
                mask: "89999999999"
            });
        });
    </script>
@endsection


