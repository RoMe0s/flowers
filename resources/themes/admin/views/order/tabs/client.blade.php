<div class="form-group @if ($errors->has('user')) has-error @endif">
    {!! Form::label('user', trans('labels.user'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">

        <select name="user_id" class="form-control select2 input-sm admin-order-user" data-id="{!! $model->id !!}">
            <option value="-1">{!! trans('labels.client') !!}</option>
            @foreach($users as $user)
                <option data-name="{!! $user->name !!}"
                        data-email="{!! $user->email !!}"
                        data-phone="{!! $user->phone !!}"
                        data-discount="{!! $user->getDiscount() !!}"
                        value="{!! $user->id !!}"
                        @if($model->user_id == $user->id || $user->id == old('user_id', -1)) selected="true" @endif>
                    {!! $user->name !!}, {!! $user->phone !!}
                </option>
            @endforeach
        </select>

        {!! $errors->first('user', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('recipient_name')) has-error @endif">
    {!! Form::label('recipient_name', trans('labels.fio'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text('recipient_name', null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.fio')]) !!}

        {!! $errors->first('recipient_name', '<p recipient_name="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('labels.email'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text('email', isset($model->user) ? $model->user->email : null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.email')]) !!}

        {!! $errors->first('email', '<p email="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('password')) has-error @endif">
    {!! Form::label('password', trans('labels.password'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3">
        {!! Form::text('password', null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.password')]) !!}

        {!! $errors->first('password', '<p password="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('recipient_phone')) has-error @endif">
    {!! Form::label('recipient_phone', trans('labels.phone'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-md-3">
        {!! Form::text('recipient_phone', null, ['placeholder' => trans('labels.phone'), 'class' => 'form-control input-sm inputmask-2', 'required' => true]) !!}

        {!! $errors->first('recipient_phone', '<p class="help-block error">:message</p>') !!}
    </div>
</div>