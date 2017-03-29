<h3>Клиент</h3>

<div class="form-group @if ($errors->has('user')) has-error @endif">
    {!! Form::label('user', trans('labels.user'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">

        @include('order.partials.users_list')

        {!! $errors->first('user', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('name', trans('labels.fio'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text(null, isset($model->user->name) ? $model->user->name : trans('labels.no') , ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'disabled' => true, 'placeholder' => trans('labels.fio')]) !!}

    </div>
</div>

<div class="form-group">
    {!! Form::label('phone', trans('labels.phone'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text(null, isset($model->user->phone) ? $model->user->phone : trans('labels.no') , ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'disabled' => true, 'placeholder' => trans('labels.phone')]) !!}

        {!! $errors->first('recipient_name', '<p recipient_name="help-block error">:message</p>') !!}
    </div>
</div>

@if($model->id)

    <div class="form-group">
        {!! Form::label('email', trans('labels.email'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

        <div class="col-xs-12 col-sm-4 col-md-3">
            {!! Form::text('email', isset($model->user) ? $model->user->email : null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'disabled' => true, 'placeholder' => trans('labels.email')]) !!}
        </div>
    </div>

@else

    <div class="form-group required @if ($errors->has('email')) has-error @endif">
        {!! Form::label('email', trans('labels.email'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

        <div class="col-xs-12 col-sm-4 col-md-3">
            {!! Form::text('email', isset($model->user) ? $model->user->email : null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.email')]) !!}

            {!! $errors->first('email', '<p email="help-block error">:message</p>') !!}
        </div>
    </div>

    {!! Form::hidden('email_required', true) !!}

@endif


<div class="form-group required @if ($errors->has('password')) has-error @endif" style="display:none;">
    {!! Form::label('password', trans('labels.password'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text('password', null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.password')]) !!}

        {!! $errors->first('password', '<p password="help-block error">:message</p>') !!}
    </div>
</div>

<h3>Данные получателя</h3>

<div class="form-group required @if ($errors->has('recipient_name')) has-error @endif">
    {!! Form::label('recipient_name', trans('labels.fio'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text('recipient_name', null , ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.fio')]) !!}

        {!! $errors->first('recipient_name', '<p recipient_name="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('recipient_phone')) has-error @endif">
    {!! Form::label('recipient_phone', trans('labels.phone'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-md-3">
        {!! Form::text('recipient_phone', null, ['placeholder' => trans('labels.phone'), 'class' => 'form-control input-sm inputmask-2', 'required' => true]) !!}

        {!! $errors->first('recipient_phone', '<p class="help-block error">:message</p>') !!}
    </div>
</div>