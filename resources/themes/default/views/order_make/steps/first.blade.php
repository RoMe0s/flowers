<div role="tabpanel" class="tab-pane fade in active" id="first">
    <div class="cells text-right">
        Сумма заказа:
        <span class="text-success price-string">{!! $cart_subtotal !!} руб.</span>
    </div>
    <br />
    {!! Form::hidden('prepay', null, ['required']) !!}
    <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
        <button type="button" class="btn btn-default btn-sm form-control" data-input="prepay" data-block="#is-present" data-block-action="hide" data-parent=".tab-content" data-value="50">
            Для себя
        </button>
    </div>
    <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
        <button type="button" class="btn btn-default btn-sm form-control" data-input="prepay" data-block="#is-present" data-block-action="show" data-parent=".tab-content" data-value="100">
            В подарок
        </button>
    </div>
    <div id="is-present">
        <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
            <label>Телефон получателя</label>
            {!! Form::input('tel', null, null, array('placeholder' => 'Телефон получателя', 'class' => 'form-control input-sm', 'data-required' => 'required', 'data-name' => 'recipient_phone')) !!}
        </div>
        <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
            <label>Имя получателя</label>
            {!! Form::text(null, null, array('placeholder' => 'Имя получателя', 'class' => 'form-control input-sm', 'data-required' => 'required', 'data-name' => 'recipient_name')) !!}
        </div>
        <div class="checkbox col-xs-12 col-sm-6 col-sm-offset-3">
            <span class="pull-right custom-popover">
                <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                   data-placement="bottom"
                   data-content="{{variable('order-anonymously')}}"></i>
            </span>
            <label>
                {!! Form::input("checkbox", null, 1, array("data-name" => 'anonymously')) !!}     
                Анонимно
            </label>
        </div>
    </div>
    <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
        <label>Текст открытки</label>
        <small class="pull-right">150 символов</small>
        {!! Form::textarea('card_text', null, ['class' => 'form-control input-sm', 'placeholder' => 'Текст открытки', 'rows' => 5, 'maxlength' => 150]) !!}
    </div>
    <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
        <button type="button" class="btn btn-success btn-sm form-control" data-next-step="#second">
            Следующий шаг
        </button>
    </div>
</div>
