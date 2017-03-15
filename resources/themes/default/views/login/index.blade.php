@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="login">
        </h2>

        <br>

        <p class="text-center">
            Ещё не зарегистрировались? <a href="{!! route('reg') !!}">Зарегистрируйтесь!</a>
        </p>

        <br>

        <div class="row">
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <p class="text-center">
                    Войдите в свой личный кабинет используя свой аккаунт Вконтакте, Facebook, Google+ и Instagram
                </p>

                <div class="row">
                    <div class="col-lg-3 col-sm-3 col-xs-3">
                        <a class="social-auth" href="/login/vkontakte/redirect">
                            <i class="fa fa-vk fa-3x"></i>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-xs-3">
                        <a class="social-auth" href="/login/facebook/redirect">
                            <i class="fa fa-facebook fa-3x"></i>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-xs-3">
                        <a class="social-auth" href="/login/google/redirect">
                            <i class="fa fa-google-plus fa-3x"></i>
                        </a>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-xs-3">
                        <a class="social-auth" href="/login/instagram/redirect">
                            <i class="fa fa-instagram fa-3x"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12">
                <form class="form" action="{!! route('post.login') !!}" method="post">
                    {!! csrf_field() !!}

                    @include('errors.form')

                    <p>
                        Email
                        <input class="form-control input-sm" type="email" name="email" value="{{ old('email') }}" required>
                    </p>
                    <p>
                        Пароль
                        <input class="form-control input-sm" type="password" name="password" value="{{ old('password') }}" required>
                    </p>
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <p>
                                Забыли пароль? <a href="{!! route('password.reset') !!}">Востановите!</a>
                            </p>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <p class="text-right">
                                <input class="btn btn-default" type="submit" value="Войти">
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br>
    </section>
@endsection