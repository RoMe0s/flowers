@extends('layouts.master')

@section('content')
    <section>

        {!! $model->content !!}

	<iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/quickpay/shop-widget?account=41001917745197&quickpay=shop&payment-type-choice=on&writer=seller&targets=%D0%94%D0%BE%D1%81%D1%82%D0%B0%D0%B2%D0%BA%D0%B0+%D1%86%D0%B2%D0%B5%D1%82%D0%BE%D0%B2&targets-hint=&default-sum=&button-text=01&comment=on&hint=&phone=on&successURL=" width="450" height="258"></iframe>

    </section>
@endsection
