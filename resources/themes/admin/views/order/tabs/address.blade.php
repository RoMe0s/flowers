<div class="form-group @if ($errors->has('address')) has-error @endif">
    {!! Form::label('address', 'Выберите адрес', ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        <select name="address_id" class="form-control select2 input-sm admin-order-address">
            <option value="-1">{!! trans('labels.address') !!}</option>
            @foreach($addresses as $address)
                <option data-address="{!! $address->address !!}"
                        data-code="{!! $address->code !!}"
                        value="{!! $address->id !!}"
                        @if($model->address_id == $address->id || $address->id == old('address_id', -1)) selected="true" @endif>
                    {!! $address->address !!}
                </option>
            @endforeach
        </select>
        {!! $errors->first('address', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('address')) has-error @endif">
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