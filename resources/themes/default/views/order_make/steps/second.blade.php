<div role="tabpanel" class="tab-pane fade" id="second">
    <div class="cells text-right">
        Сумма заказа:
        <span class="text-success price-string">{!! Cart::subtotal() + get_delivery_price() !!} руб.</span>
    </div>
    <br/>
    <div class="col-xs-12 col-sm-6 half">
        <h4 class="text-center">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            Адрес
        </h4>
        <br/>
        {!! Form::hidden('address_string', null, ['id' => 'address_field', 'required']) !!}
        {!! Form::hidden('specify', null, ['id' => 'specify_field', 'required']) !!}
        <div class="form-group">
                    <span class="pull-right custom-popover">
                        <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                           data-placement="bottom"
                           data-content="{{variable('order-specify')}}"></i>
                    </span>
            <button type="button" class="btn btn-default btn-sm form-control" data-input="specify" data-parent=".half"
                    data-block="#is-courier-delivery" data-block-action="hide" data-value="1">
                Уточнить у получателя
            </button>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default btn-sm form-control" data-input="address_string"
                    data-block="#is-courier-delivery"
                    data-block-action="hide" data-parent=".half" data-value="Самовывоз">
                Самовывоз
            </button>
            <p class="col-xs-12 text-center our-address">
                <b>{!! variable('city') !!}, {!! variable('address') !!}</b>
            </p>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-default btn-sm form-control" data-block="#is-courier-delivery"
                    data-block-action="show" data-parent=".half" data-input="address" data-value="courier"
                    data-change-required="#address_field, #specify_field"
                    data-except="new-address-input">
                Доставка курьером
            </button>
        </div>
        <div id="is-courier-delivery">
            <div class="form-group">
                        <span class="pull-right custom-popover">
                            <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                               data-placement="bottom"
                               data-content="{{variable('order-neighbourhood')}}"></i>
                        </span>
                <label>Оставить соседям</label>
                {!! Form::select(null, [0 => 'Нет', '1' => 'Да'], null, ['class' => 'form-control input-sm', 'data-name' => 'neighbourhood']) !!}
            </div>
            <div class="form-group">
                        <span class="pull-right custom-popover">
                            <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                               data-placement="bottom"
                               data-content="{{variable('order-isset-address')}}"></i>
                        </span>
                <label>Существующий адрес</label>
                {!! Form::select(null, $addresses->lists('address', 'id'), null, ['class' => 'form-control input-sm', 'data-name' => 'address_id', 'placeholder' => 'Существующий адрес', 'data-required']) !!}
            </div>
            <button class="col-xs-12 text-center btn btn-default btn-sm like-href" data-input data-block="#new-address"
                    data-block-action="both" type="button" data-change-required="select[data-name='address_id']">
                Или добавить новый
            </button>
            <div id="new-address">
                <div class="form-group">
                    <label>Город</label>
                    {!! Form::text(null, null, ['class' => 'form-control input-sm', 'placeholder' => 'Город', 'data-required', 'data-name' => 'address[city]', 'data-except' => 'new-address-input']) !!}
                </div>
                <div class="form-group">
                    <label>Улица</label>
                    {!! Form::text(null, null, ['class' => 'form-control input-sm', 'placeholder' => 'Улица', 'data-required', 'data-name' => 'address[street]', 'data-except' => 'new-address-input']) !!}
                </div>
                <div class="form-group">
                    <label>Дом</label>
                    {!! Form::text(null, null, ['class' => 'form-control input-sm', 'placeholder' => 'Дом', 'data-required', 'data-name' => 'address[house]', 'data-except' => 'new-address-input']) !!}
                </div>
                <div class="form-group">
                    <label>Квартира</label>
                    {!! Form::text(null, null, ['class' => 'form-control input-sm', 'placeholder' => 'Квартира', 'data-name' => 'address[flat]', 'data-except' => 'new-address-input']) !!}
                </div>
                <div class="form-group">
                    <label>Код домофона</label>
                    {!! Form::text(null, null, ['class' => 'form-control input-sm', 'placeholder' => 'Код домофона', 'data-name' => 'address[code]', 'data-except' => 'new-address-input']) !!}
                </div>
                <div class="form-group">
                            <span class="pull-right custom-popover">
                                <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                                   data-placement="bottom" data-content="{{variable('order-distance')}}"></i>
                            </span>
                    <label>Удаленность от МКАД</label>
                    {!! Form::text(null, 0, ['class' => 'form-control input-sm', 'placeholder' => 'Удаленность от МКАД', 'data-name' => 'address[distance]', 'data-required', 'data-except' => 'new-address-input', 'data-price' => 'mkad', 'data-used' => '0']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <h4 class="text-center">
            <i class="fa fa-clock-o" aria-hidden="true"></i>
            Дата доставки
        </h4>
        <br/>
        <div class="form-group">
            <label>Дата доставки</label>
            {!! Form::text('date', null, array('class' => 'form-control input-sm', 'required')) !!}
        </div>
        <div class="form-group">
            <label>Время доставки</label>
            {!! Form::select('time', $times, null, array('class' => 'form-control input-sm', 'required')) !!}
        </div>
        <div class="form-group" style="display: none;">
                    <span class="pull-right custom-popover">
                        <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                           data-placement="bottom"
                           data-content="{{variable('order-night')}}"></i>
                    </span>
            <label>Ночная доставка</label>
            {!! Form::select(null, [0 => 'Нет', '1' => 'Да'], null, ['class' => 'form-control', 'placeholder' => 'Ночная доставка', 'data-name' => 'night', 'data-price' => '800', 'data-used' => '0']) !!}
        </div>
        <div class="form-group">
                    <span class="pull-right custom-popover">
                        <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                           data-placement="bottom"
                           data-content="{{variable('order-accuracy')}}"></i>
                    </span>
            <label>Точность до 15 минут</label>
            {!! Form::select('accuracy', [0 => 'Нет', '1' => 'Да'], null, ['class' => 'form-control', 'placeholder' => 'Точность до 15 минут', 'data-name' => 'accuracy', 'required', 'data-price' => '300', 'data-used' => '0']) !!}
        </div>
        <div class="form-group">
            <label>Комментарий</label>
            {!! Form::textarea("comment", null, ['class' => 'form-control input-sm', 'placeholder' => 'Комментарий', 'rows' => 5]) !!}
        </div>
    </div>
    <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
        <button type="button" class="btn btn-success btn-sm form-control" data-next-step="#third">
            Следующий шаг
        </button>
    </div>
</div>