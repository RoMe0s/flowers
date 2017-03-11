@extends('layouts.master')

@section('content')
    <h3 class="text-center">
        {!! $model->name !!}
    </h3>

    <form style="width: 250px; margin: 20px auto 0;" class="form" action="{!! route('post.password.reset') !!}" method="post">
        {!! csrf_field() !!}

        <p>
            Email
            <input class="form-control" type="email" name="email" required>
        </p>
        <p class="text-center">
            <input class="btn btn-default" type="submit" value="Востановить">
        </p>
    </form>
@endsection
