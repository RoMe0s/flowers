@extends('layouts.master')

@section('content')

    <h3 class="text-center">
        {!! $model->name !!}
    </h3>

    <section class="clearfix">
        @include('errors.form')
        <form class="form col-md-8 col-md-offset-2" action="{!! route('post.password.reset') !!}" method="post">
            {!! csrf_field() !!}
            <p>
            <div class="input-group">
                <input class="form-control input-sm" type="text" name="login" value="{{ old('login') }}" required data-phone_input="true">
                <span class="input-group-addon btn-purple" id="use-email">
                                С помощью Email
                            </span>
            </div>
            </p>
            <p class="text-center">
                <input class="btn btn-default" type="submit" value="Востановить">
            </p>
        </form>
    </section>

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