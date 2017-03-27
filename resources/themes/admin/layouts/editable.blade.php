@extends('layouts.main')

@section('assets.top')
    @parent
    <link rel="stylesheet" href="{!! asset('assets/components/lightbox2/dist/css/lightbox.min.css') !!}"/>

    <script src="{!! asset('assets/themes/admin/vendor/adminlte/plugins/ckeditor/ckeditor.js') !!}"></script>

    <script src="{!! asset('assets/components/sysTranslit/js/jquery.synctranslit.min.js') !!}"></script>
@stop


@section('assets.bottom')
    @parent
    <script src="{!! asset('assets/components/lightbox2/dist/js/lightbox.min.js') !!}"></script>
@endsection
