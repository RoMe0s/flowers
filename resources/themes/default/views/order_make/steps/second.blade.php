<div role="tabpanel" class="tab-pane fade" id="second">
    <div class="cells text-right">
        Сумма заказа:
        <span class="text-success price-string">{!! $cart_subtotal !!} руб.</span>
    </div>
    <br/>
    <div class="col-xs-12 col-sm-6 half">
        <h4 class="text-center">
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            Адрес
        </h4>
        <br/>
        {!! Form::hidden('address_string', null, ['id' => 'address_field', 'required', 'data-name' => 'address_string']) !!}
        {!! Form::hidden('specify', null, ['id' => 'specify_field', 'required', 'data-name' => 'specify']) !!}
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
            <button class="col-xs-12 text-center btn btn-default btn-sm like-href" data-input data-block="#new-address"
                    data-block-action="both" type="button" data-change-required="select[data-name='address_id']">
                Добавить новый
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
            </div>
            <div class="form-group" style="margin-bottom: 10px;">
                        <span class="pull-right custom-popover">
                            <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                               data-placement="bottom"
                               data-content="{{variable('order-isset-address')}}"></i>
                        </span>
                <label>Существующий адрес</label>
                {!! Form::select(null, $addresses->lists('address', 'id'), null, ['class' => 'form-control input-sm', 'data-name' => 'address_id', 'placeholder' => 'Существующий адрес', 'data-required']) !!}
            </div>
            <div class="row clearfix">
                <div class="col-xs-12 parent-for-inputs"  style="margin: 5px 0;">
                    <div class="checkbox">
                        <span class="pull-right custom-popover">
                            <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                               data-placement="bottom" data-content="{{variable('order-distance-5')}}"></i>
                        </span>
                        <label>
                            {!! Form::input("checkbox", null, 5, ['data-name' => 'distance', 'data-price' => variable('mkad-delivery-5', 250), 'data-used' => '0']) !!}
                            Доставка за МКАД (до 5 км.)&nbsp;
                            <span class="text-info">+{!! variable('mkad-delivery-5', 250) !!} руб.</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <span class="pull-right custom-popover">
                            <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                               data-placement="bottom" data-content="{{variable('order-distance-10')}}"></i>
                        </span>
                        <label>
                            {!! Form::input("checkbox", null, 10, ['data-name' => 'distance', 'data-price' => variable('mkad-delivery-10', 500), 'data-used' => '0']) !!}
                            Доставка за МКАД (до 10 км.)&nbsp;
                            <span class="text-info">+{!! variable('mkad-delivery-10', 500) !!} руб.</span>
                        </label>
                    </div>
                    <div class="checkbox">
                        <label>
                            {!! Form::input("checkbox", null, 11, ['data-name' => 'distance', 'data-used' => '0', 'data-price' => '0']) !!}
                            Доставка за МКАД (более 10 км.)&nbsp;
                            <span class="text-info">По согласованию</span>
                        </label>
                    </div>
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
        <div class="checkbox" style="display: none;">
            <span class="pull-right custom-popover">
                <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                   data-placement="bottom"
                   data-content="{{variable('order-night')}}"></i>
            </span>
            <label style="pointer-events: none;">
                {!! Form::input('checkbox', null, 1, ['data-name' => 'night', 'data-price' => variable('night-delivery', 800), 'data-used' => '0']) !!}
                Ночная доставка&nbsp;
                <span class="text-info">+{!! variable('night-delivery', 800) !!} руб.</span>
            </label>
        </div>
        <div class="checkbox">
            <span class="pull-right custom-popover">
                <i class="fa fa-question-circle-o" aria-hidden="true" data-toggle="popover"
                   data-placement="bottom"
                   data-content="{{variable('order-accuracy')}}"></i>
            </span>
            <label>
                {!! Form::input("checkbox", 'accuracy',  1, ['data-name' => 'accuracy', 'data-price' => variable('accuracy-delivery' ,300), 'data-used' => '0']) !!}
                Доставка к точному времени (точность до 15 минут)
                <span class="text-info">+{!! variable('accuracy-delivery', 300) !!} руб.</span>
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::input("checkbox", "neighbourhood" , 1) !!}
                Если получателя не будет на месте, оставить заказ родственникам/друзьям/коллегам
            </label>
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
