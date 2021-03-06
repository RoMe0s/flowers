
<div class="form-group @if ($errors->has('courier_id')) has-error @endif">
    {!! Form::label('courier_id', trans('labels.courier'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::select('courier_id', $couriers , null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'placeholder' => trans('labels.courier')]) !!}

        {!! $errors->first('courier_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('discount')) has-error @endif">
    {!! Form::label('discount', trans('labels.discount') . ' '.  trans('labels.percent'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::text('discount', $discount , ['placeholder' => trans('labels.discount'), 'id' => 'order-discount', 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('discount', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('delivery_price')) has-error @endif">
    {!! Form::label('delivery_price', trans('labels.delivery_price'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::text('delivery_price', $model->delivery_price ?: 0, ['placeholder' => trans('labels.delivery_price'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('delivery_price', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('static_discount')) has-error @endif">
    {!! Form::label('static_discount', "Статическая скидка", ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::text('static_discount', $model->static_discount ?: 0, ['placeholder' => "Статическая скидка", 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('static_discount', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('date')) has-error @endif">
    {!! Form::label('date', trans('labels.date'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        <div class="input-group">
            {!! Form::text('date', null, ['placeholder' => trans('labels.date'), 'class' => 'form-control input-sm inputmask-birthday datepicker-birthday']) !!}
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>

        {!! $errors->first('date', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('time')) has-error @endif">
    {!! Form::label('time', trans('labels.time'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::select('time', $times, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'placeholder' => trans('labels.time')]) !!}

        {!! $errors->first('time', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('accuracy')) has-error @endif">
    {!! Form::label('accuracy', 'Точность до 15 минут', ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::select('accuracy', [0 => 'Нет', 1 => 'Да'], isset($model->accuracy) ? $model->accuracy : 0, ['id' => 'order-accuracy', 'class' => 'form-control input-sm select2', 'required' => true]) !!}

        {!! $errors->first('accuracy', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('night')) has-error @endif">
    {!! Form::label('night', 'Ночная доставка', ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::select('night', [0 => 'Нет', 1 => 'Да'], isset($model->night) ? $model->night : 0, ['id' => 'order-night', 'class' => 'form-control input-sm select2', 'required' => true]) !!}

        {!! $errors->first('night', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('prepay')) has-error @endif">
    {!! Form::label('prepay', trans('labels.prepay'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::select('prepay', $prepay, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('prepay', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::select('status', $statuses, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('card_text')) has-error @endif">
    {!! Form::label('card_text', trans('labels.card_text'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::textarea('card_text', null, ['placeholder' => trans('labels.card_text'), 'class' => 'form-control input-sm', 'style' => 'resize:none;']) !!}

        {!! $errors->first('card_text', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('desc')) has-error @endif">
    {!! Form::label('desc', trans('labels.desc'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

    <div class="col-xs-12 col-sm-9">
        {!! Form::textarea('desc', null, ['placeholder' => trans('labels.desc'), 'class' => 'form-control input-sm', 'style' => 'resize:none;']) !!}

        {!! $errors->first('desc', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
