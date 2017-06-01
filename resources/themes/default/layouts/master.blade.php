<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- For EDGE and IE -->
    <meta http-equiv = "X-UA-COMPATIBLE" content = "IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {!! Meta::render() !!}
    <!-- Icons -->
    <link rel = "shortcut icon" type = "image/x-icon" href = "{{ Theme::asset('images/logo.png') }}">
    <link rel = "apple-touch-icon" type = "image/x-icon" href = "{{ Theme::asset('images/logo.png') }}">
    <!-- Skype toolbar tags -->
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE">

    <!-- Yandex Metric tags -->
    <meta name="yandex-verification" content="624510927ff571d1">

    @section('styles')
        @include('partials.styles')
    @show

</head>
<body @if(request()->url() == route('home')) itemscope itemtype="http://schema.org/Florist" @endif>
<div id="notifications"></div>

    @section('header')
        @include('partials.header')
    @show

    <main>
        @yield('content')
    </main>

    @section('footer')
        @include('partials.footer')
    @show


    @section('popups')

    @show

    {!! Form::open(['method' => 'POST', 'route' => 'cart.popupLoad', 'ajax', 'postAjax' => 'basket-popup-loaded', 'id' => 'cart-popup-load']) !!}
    {!! Form::close() !!}
    @include('popups.basket')

    @section('scripts')
        @include('partials.scripts')
    @show
</body>
</html>
