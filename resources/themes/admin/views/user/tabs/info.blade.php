<div class="tab-pane active" id="settings">
    <div class="form-group required @if ($errors->has('name')) has-error @endif">
        {!! Form::label('name', trans('labels.fio'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-5">
            {!! Form::text('name', null, ['placeholder' => trans('labels.fio'), 'required' => true, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('name', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required @if ($errors->has('email')) has-error @endif">
        {!! Form::label('email', trans('labels.email'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('email', null, ['placeholder' => trans('labels.email'), 'required' => true, 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('email', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group @if ($errors->has('phone')) has-error @endif">
        {!! Form::label('phone', trans('labels.phone'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('phone', null, ['placeholder' => trans('labels.phone'), 'class' => 'form-control input-sm inputmask-2']) !!}

            {!! $errors->first('phone', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group @if ($errors->has('start_discount')) has-error @endif">
        {!! Form::label('start_discount', trans('labels.start_discount') . ' ' . trans('labels.vip'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('start_discount', null, ['placeholder' => trans('labels.start_discount'), 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('start_discount', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group @if ($errors->has('discount')) has-error @endif">
        {!! Form::label('discount', trans('labels.discount'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-md-3">
            {!! Form::text('discount', null, ['placeholder' => trans('labels.discount'), 'class' => 'form-control input-sm']) !!}

            {!! $errors->first('discount', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    <div class="form-group required @if ($errors->has('notifications')) has-error @endif">
        {!! Form::label('notifications', trans('labels.notifications'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-xs-3">
            {!! Form::select('notifications', ['0' => trans('labels.no'), '1' => trans('labels.yes'),], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

            {!! $errors->first('notifications', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    @if(empty($model->id))
        <div class="form-group @if ($errors->has('password')) has-error @endif">
            {!! Form::label('password', trans('labels.password'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-3">
                {!! Form::text('password', null, ['placeholder' => trans('labels.password'), 'required' => true, 'class' => 'form-control input-sm']) !!}

                {!! $errors->first('password', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>

        <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
            {!! Form::label('password_confirmation', trans('labels.password_confirmation'), ['class' => 'col-md-3 control-label']) !!}

            <div class="col-md-3">
                {!! Form::text('password_confirmation', null, ['placeholder' => trans('labels.password_confirmation'), 'required' => true, 'class' => 'form-control input-sm']) !!}

                {!! $errors->first('password_confirmation', '<p class="help-block error">:message</p>') !!}
            </div>
        </div>
    @endif

    <div class="form-group required @if ($errors->has('activated')) has-error @endif">
        {!! Form::label('activated', trans('labels.activated'), ['class' => 'col-md-3 control-label']) !!}

        <div class="col-xs-3">
            {!! Form::select('activated', ['0' => trans('labels.no'), '1' => trans('labels.yes'),], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

            {!! $errors->first('activated', '<p class="help-block error">:message</p>') !!}
        </div>
    </div>

    @include('partials.tabs.fields')

</div>
