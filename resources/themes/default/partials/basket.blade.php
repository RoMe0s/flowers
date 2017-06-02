<div class="modal-header">
    <a class="close pull-left" data-dismiss="modal" aria-label="Close" style="font-size: 36px">
        <span aria-hidden="true">&times;</span>
    </a>
    <h4 class="modal-title">
        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
        <span>
            Ваша корзина
            <b id="basket-count-total"> 
                ({!! Cart::count() !!})
            </b>
        </span>
    </h4>
</div>
<div class="modal-body">
    <ul class="basket-list clearfix">
        @foreach($items as $item)
            <li class="col-xs-12" data-basket-row-id="{{$item->rowid}}">
                <div class="basket-item-image">
                    <a href="{!! $item->options['url'] !!}" target="_blank" title="{{$item->name}}"
                       class="basket-item-thumb">
                        <img src="{!! thumb($item->options['image'], 90) !!}" alt="{{$item->name}}"/>
                    </a>
                </div>
                <div class="basket-item-info">
                    {!! Form::open(['ajax', 'postAjax' => 'basket-item-changed', 'method' => 'POST', 'route' => 'basket.item.change']) !!}
                    {!! Form::hidden('rowid', $item->rowid) !!}
                    {!! Form::hidden("method", null) !!}
                    <div class="basket-item-name col-xs-12">
                        <a href="{!! $item->options['url'] !!}" target="_blank" title="{{$item->name}}"
                           class="basket-item-title">
                            {{$item->name}}
                        </a>
                        &nbsp;
                        <button class="like-href text-danger basket-item-title" data-method="remove" type="button">
                            <i class="fa fa-times-circle text-danger" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="basket-item-count col-xs-8">
                        <div class="input-group pull-left col-xs-5">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-method="less">-</button>
                            </span>
                            {!! Form::text('count', $item->qty, ['class' => 'form-control text-center', 'placeholder' => 'Кол-во', 'disabled']) !!}
                            <span class="input-group-btn">
                                <button class="btn btn-default" data-method="more" type="button">+</button>
                            </span>
                        </div>
                        <span class="text-success basket-item-price text-center pull-right">
                            {!! $item->subtotal !!} руб.
                        </span>
                    </div>
                    {!! Form::close() !!}
                </div>
            </li>
        @endforeach
        @if(!sizeof($items))
            <li>
                <h4 class="text-center">
                    Корзина пуста
                </h4>
            </li>
        @endif
        @if(sizeof($items) && !$user)
            <li class="col-xs-12 text-center" data-question style="display: none;">
                <h4>
                    Заказывали раньше?
                </h4>
                <a href="{!! route('login') !!}" title="Перейти на страницу входа"
                   class="btn btn-sm btn-purple col-xs-12" style="margin-bottom: 10px;">
                    Да, перейти на страницу входа
                </a>
                <a href="{!! route('reg') !!}" title="Перейти на страницу регистрации"
                   class="btn btn-sm btn-purple col-xs-12">
                    Нет, перейти на страницу регистрации
                </a>
            </li>
        @endif
    </ul>
</div>
@if(sizeof($items))
    <div class="modal-footer">
        <p class="text-center">
            Сумма:
            <span class="text-success" id="basket-subtotal">
            {{Cart::subtotal() + get_delivery_price()}} руб.
        </span>
        </p>
        <a class="btn btn-purple col-xs-12" @if($user) href="{!! route('get.order') !!}"
           @else data-show-question @endif>
            Оформить заказ
        </a>
        <a href="#" data-dismiss="modal" aria-label="Close" class="col-xs-12 text-center footer-close">
            Продолжить попкупки
        </a>

    </div>
@endif
