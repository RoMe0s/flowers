@extends('layouts.master')

@section('content')
    <h3 class="text-center">УКАЖИТЕ НОВЫЙ ПАРОЛЬ</h3>

    <form style="width: 250px; margin: 20px auto 0;" class="form" action="" method="post">
        {!! csrf_field() !!}

        @if(session('status'))
            <p class="alert alert-success">
                На ваш email отправлена ссылка востановления пароля.
            </p>
        @endif

        <p>
            Email
            <input class="form-control" type="email" name="email" value="{{ $email }}" required>
        </p>
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