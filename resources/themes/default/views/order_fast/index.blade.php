@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="doc">
        </h2>

        {!! $model->content !!}

        <div class="row">
            <div class="col-sm-4 col-xs-12 col-sm-offset-4">
                @include('errors.form')

                <form action="{!! route('post.fast.order') !!}" method="post">
                    {!! csrf_field() !!}

                    <p>
                        <input class="form-control" type="tel" name="phone" placeholder="Номер телефона" value="{{ old('phone') }}" required>
                    </p>
                    <p>
                        <input class="form-control" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                    </p>
                    <p>
                        <input type="checkbox" name="agreement" value="1" required> Я ознакомился(-лась) и согласен(-сна) с <a href="{{ url('/offer') }}" target="_blank">условиями Публичной ОФЕРТЫ</a>.
                    </p>
                    <p class="text-center">
                        <input class="btn btn-purple" type="submit" value="Оформить">
                    </p>
                </form>
            </div>
        </div>
    </section>
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