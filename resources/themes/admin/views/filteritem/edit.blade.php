@extends('layouts.editable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            {!! Form::model($model, array('enctype'=>'multipart/form-data', 'role' => 'form', 'method' => 'put', 'class' => 'form-horizontal', 'route' => array('admin.filteritem.update', $model->id))) !!}

            @include('filteritem.partials._form')

            {!! Form::close() !!}
        </div>
    </div>

@stop
