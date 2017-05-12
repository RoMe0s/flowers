@extends('layouts.master')

@section('content')
    <section>
        <div class="row">
            <div class="col-lg-3 col-sm-3 col-xs-12">
                @widget__menu(2)
            </div>
            <div class="col-lg-9 col-sm-9 col-xs-12">
                <h2 class="text-center">{!! $title ?: $model->name !!}</h2>

                @include('errors.form')

                @section('profile-content')
                    <div class="col-md-offset-3 col-md-6">
                        <form action="{!! route('profile.post') !!}" method="post">
                            {{ csrf_field() }}

                            <p>
                                ФИО
                                <input class="form-control input-sm" type="text" name="name" value="{{ $user->name }}" @if(!empty($user->name)) required @endif>
                            </p>
                            <p>
                                Email
                                <input class="form-control input-sm" type="email" name="email" value="{{ $user->email }}" @if(!empty($user->email)) disabled @endif>
                            </p>

                            <p>
                                Телефон
                                <input name="phone" class="form-control input-sm" type="tel" value="{{ $user->phone }}" @if(!empty($user->phone)) disabled @endif>
                            </p>
                            <p class="text-right">
                                <input class="btn btn-default" type="submit" value="Изменить">
                            </p>
                        </form>
                    </div>
                @show
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <br />
@endsection