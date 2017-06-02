<div class="form-group @if ($errors->has('address_string')) has-error @endif">
    {!! Form::label('address_string', 'Другой тип доставки', ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::text('address_string', null , ['placeholder' => 'Другой тип доставки', 'class' => 'form-control input-sm select2', 'id' => 'delivery_another_way']) !!}

        {!! $errors->first('address_string', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('address')) has-error @endif">

    {!! Form::label('address', 'Выберите адрес', ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        <select name="address_id" class="form-control select2 input-sm admin-order-address">
            <option value="-1">{!! trans('labels.address') !!}</option>
            @foreach($addresses as $address)
                <option data-address="{!! $address->address !!}"
                        data-code="{!! $address->code !!}"
                        data-distance="{!! $address->distance ? : 0 !!}"
                        value="{!! $address->id !!}"
                        @if($model->address_id == $address->id || $address->id == old('address_id', -1)) selected="true" @endif>
                    {!! $address->address !!}
                </option>
            @endforeach
        </select>
        {!! $errors->first('address', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="delivery_another_way form-group required @if ($errors->has('address')) has-error @endif">
    {!! Form::label('address', trans('labels.address'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::textarea('address', isset($model->address) ? $model->address->address : null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.address'), 'style' => 'resize: none;']) !!}

        {!! $errors->first('address', '<p address="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('code')) has-error @endif">
    {!! Form::label('code', trans('labels.code'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::text('code', isset($model->address) ? $model->address->code : null, ['placeholder' => trans('labels.code'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('code', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('distance')) has-error @endif">
    {!! Form::label('distance', 'Отдаленность от МКАД', ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::text('distance', null, ['placeholder' => 'Отдаленность от МКАД', 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('distance', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('neighbourhood')) has-error @endif">
    {!! Form::label('neighbourhood', 'Оставить соседям', ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::select('neighbourhood', [0 => 'Нет', 1 => 'Да'], null, ['placeholder' => 'Оставить соседям', 'class' => 'form-control input-sm select2']) !!}

        {!! $errors->first('neighbourhood', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
