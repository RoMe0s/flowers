@extends('layouts.main')

@section('assets.top')
    @parent


    <link rel="stylesheet" href="{!! asset('assets/components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css') !!}"/>

    <link rel="stylesheet" href="{!! asset('assets/components/lightbox2/dist/css/lightbox.min.css') !!}"/>

@endsection

@section('assets.bottom')
    @parent

    <script src="{!! asset('assets/components/bootstrap-switch/dist/js/bootstrap-switch.min.js') !!}"></script>

    <script src="{!! asset('assets/components/lightbox2/dist/js/lightbox.min.js') !!}"></script>

    <script>
        lightbox.option({
            'resizeDuration' : 100,
            'disableScrolling' : true,
            'fadeDuration' : 100,
            'wrapAround' : true
        });
    </script>

@endsection