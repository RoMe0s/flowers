@extends('layouts.master')

@section('content')
    <h3 class="text-center">{!! $model->name !!}</h3>

    <section>
        @include('errors.form')
    </section>


    <form style="width: 250px; margin: 20px auto 0;" class="form" action="{!! route('post.password.token', ['login' => $login, 'token' => $token]) !!}" method="post">
        {!! csrf_field() !!}
        <p>
            Новый пароль
            <input class="form-control" type="password" name="password" required>
        </p>
        <p>
            Повторите пароль
            <input class="form-control" type="password" name="password_confirmation" required>
        </p>
        <p class="text-center">
            <input type="hidden" name="token" value="{{ $token }}">
            <input class="btn btn-default" type="submit" value="Востановить">
        </p>
    </form>

@endsection