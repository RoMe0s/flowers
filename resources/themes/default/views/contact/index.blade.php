@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
        </h2>

        {!! $model->content !!}

        <form action="{!! route('api.feedback') !!}" method="post">
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-lg-6  col-sm-6 col-xs-12">
                    <p>
                        Имя
                        <input class="form-control" type="text" name="name" value="{{ old('name') }}" required>
                    </p>
                    <p>
                        Email
                        <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
                    </p>
                </div>
                <div class="col-lg-6  col-sm-6 col-xs-12">
                    <p>
                        Сообщение
                        <textarea class="form-control" name="message" rows="5" required></textarea>
                    </p>
                    <p class="text-right">
                        <input class="btn btn-default" type="submit" value="ОТПРАВИТЬ">
                    </p>
                </div>
            </div>
        </form>
    </section>
@endsection