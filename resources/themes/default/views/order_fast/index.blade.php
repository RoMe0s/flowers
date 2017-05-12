@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="doc">
        </h2>

        {!! $model->content !!}
        @if(!isset($user))
            <p class="text-center">
                Ещё не заказывали?
                <a class="purple" href="{!! route('reg') !!}">
                    <b>
                        Создать учетную запись!
                    </b>
                </a>
            </p>
        @endif
        <br />

        <div class="row">
            <div class="col-sm-4 col-xs-12 col-sm-offset-4">
                @include('errors.form')

                <form action="{!! route('post.fast.order') !!}" method="post">
                    {!! csrf_field() !!}
                    <p>
                        @php($phone = isset($user) && isset($user->phone) ? substr_replace($user->phone,'7',0,1) : null)
                        {!! Form::input("tel", "phone", $phone, array('class' => 'form-control', 'placeholder' => 'Номер телефона')) !!}

                    </p>
                    @if(!isset($user))
                        <p>
                            <input class="form-control" type="password" name="password" placeholder="Пароль" value="{{ old('email') }}">
                        </p>
                    @endif
                    <p>
                        <input required type="checkbox" name="agreement" value="1"> Я ознакомился(-лась) и согласен(-сна) с <a href="{{ url('/offer') }}" target="_blank">условиями Публичной ОФЕРТЫ</a>.
                    </p>
                    <p class="text-center">
                        <input class="btn btn-purple" type="submit" value="Оформить" onclick="yaCounter29938144.reachGoal('addfastorder'); return true;">
                    </p>
                </form>
            </div>
        </div>
    </section>

@endsection