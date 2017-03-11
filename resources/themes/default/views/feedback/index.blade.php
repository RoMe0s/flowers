@extends('layouts.master')

@section('content')
    <section>
        <h1 class="text-center">{!! $model->name !!}</h1>

        {!! $model->content !!}

        <div class="col-md-offset-4 col-md-4">

            @include('errors.form')

            <form action="{!! route('store.feedback') !!}" method="post">

                {{ csrf_field() }}

                <p>
                    <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Имя" required>
                </p>
                <p>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                </p>
                <p>
                    <input class="form-control" type="text" name="phone" value="{{ old('phone') }}" placeholder="Телефон" required>
                </p>
                <p>
                    <textarea class="form-control" rows="7" name="text" placeholder="Сообщение" required>{{ old('text') }}</textarea>
                </p>
                <p class="text-right">
                    <input class="btn btn-danger" type="submit" value="Отправить">
                </p>
            </form>
        </div>
        <div class="clearfix"></div>
    </section>
@endsection