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
                <a href="#" title="Да"
                   class="btn btn-sm btn-purple col-xs-12" style="margin-bottom: 10px;" data-answer="login">
                    Да
                </a>
                <a href="#" title="Нет"
                   class="btn btn-sm btn-purple col-xs-12" data-answer="register">
                    Нет
                </a>
            </li>
            <li data-answer="login" style="display: none">
                <h2 class="text-center">
                    Вход
                </h2>
                {!! Form::open(["method" => "POST", "route" => "post.login", "class" => "col-xs-12", "answer-form" => "login", "ajax"]) !!}
                {!! Form::hidden("redirect", route('get.order')) !!}
                <div class="form-group">
                    <label>
                        Логин
                    </label>
                    <div class="input-group">
                        @php($mask = preg_match('/@/',old('login', '')) ? 'false' : 'true')
                        <input class="form-control input-sm" type="text" name="login" value="{{ old('login') }}" required data-phone_input="{!! $mask !!}"/>
                        <span class="input-group-addon btn-purple" data-use-email>
                            @if($mask == 'true')
                                С помощью Email
                            @else
                                По номеру
                            @endif
                    </span>
                    </div>
                </div>
                <div class="form-group">
                    <label>
                        Пароль
                    </label>
                    {!! Form::input("password", "password", null, ['class' => 'form-control input-sm', 'required']) !!}
                </div>
                {!! Form::close() !!}
            </li>
            <li data-answer="register" style="display: none">
                <h2 class="text-center">
                    Регистрация
                </h2>
                {!! Form::open(["method" => "POST", "route" => "reg", "ajax", "class" => "col-xs-12", "answer-form" => "register"]) !!}
                {!! Form::hidden("redirect", route('get.order')) !!}
                <div class="form-group">
                    <label>
                        Номер телефона
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    {!! Form::input("tel", "phone", null, ['class' => 'form-control input-sm', 'required'] ) !!}
                </div>
                <div class="form-group">
                    <label>
                        ФИО
                    </label>
                    {!! Form::text("name", null, ['class' => 'form-control input-sm']) !!}
                </div>
                <div class="form-group">
                    <label>
                        Email
                    </label>
                    {!! Form::input("email", "email", null, ['class' => 'form-control input-sm']) !!}
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="agreement" value="1" required @if( old('agreement') ) checked @endif>
                        Я ознакомился(-лась) и согласен(-сна) с <a href="{{ url('/offer') }}" target="_blank">условиями Публичной ОФЕРТЫ</a>.
                    </label>
                </div>
                {!! Form::close() !!}
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
