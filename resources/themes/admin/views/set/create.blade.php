@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::open(array('enctype'=>'multipart/form-data', 'route' => 'admin.set.store', 'role' => 'form', 'class' => 'form-horizontal')) !!}

            @include('views.set.partials._form')

            {!! Form::close() !!}
        </div>
    </div>
@stop