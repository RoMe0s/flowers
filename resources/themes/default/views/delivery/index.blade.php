@extends('layouts.master')

@section('content')
<section>
    <h1 class="text-center">{!! $model->name !!}</h1>
    <br />
    <div class="row">
        <div class="col-sm-3  text-center">
            <p><i class="fa fa-shopping-basket fa-3x fa-purple"></i></p>

            <p>Заявка на сайте</p>
        </div>

        <div class="col-sm-3 text-center">
            <p><i class="fa fa-phone fa-3x fa-purple"></i></p>

            <p>Подтверждение заказа</p>
        </div>

        <div class="col-sm-3 text-center">
            <p><i class="fa fa-credit-card fa-3x fa-purple"></i></p>

            <p>Оплата заказа</p>
        </div>

        <div class="col-sm-3 text-center">
            <p><i class="fa fa-car fa-3x fa-purple"></i></p>

            <p>Моментальная доставка по Москве</p>
        </div>
    </div>

    {!! $model->content !!}

</section>
@endsection