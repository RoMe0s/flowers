@extends('layouts.master')

@section('content')
    <h3 class="text-center">
        {!! $model->name !!}
    </h3>

    <section>
        @include('errors.form')
    </section>

    <form style="width: 250px; margin: 20px auto 0;" class="form" action="{!! route('post.password.reset') !!}" method="post">
        {!! csrf_field() !!}

        <p>
            <input class="form-control" type="text" name="login" placeholder="Телефон или Email" required>
        </p>
        <p class="text-center">
            <input class="btn btn-default" type="submit" value="Востановить">
        </p>
    </form>
@endsection


@section('popups')
    @if(session()->has('password_reset_status') && session('password_reset_status') === "success" && session()->has('reset_password_login'))
        @include('popups.password_reset', ['login' => session('reset_password_login')])
    @endif
@endsection

@section('scripts')
    @parent
    @if(session()->has('password_reset_status') && session('password_reset_status') === "success" && session()->has('reset_password_login'))
        <script src="{!! Theme::asset('js/auth.js') !!}"></script>
    @endif
@endsection