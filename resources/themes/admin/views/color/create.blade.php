@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(array('enctype'=>'multipart/form-data', 'route' => 'admin.color.store', 'role' => 'form', 'class' => 'form-horizontal')) !!}

            @include('views.color.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop