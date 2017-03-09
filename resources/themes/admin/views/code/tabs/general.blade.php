<div class="form-group required @if ($errors->has('code')) has-error @endif">
    {!! Form::label('code', trans('labels.code'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('code', null, ['placeholder' => trans('labels.code'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('code', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('discount')) has-error @endif">
    {!! Form::label('discount', trans('labels.discount'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('discount', null, ['placeholder' => trans('labels.discount') .' '. trans('labels.percent'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('discount', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('date')) has-error @endif">
    {!! Form::label('date', trans('labels.date'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        <div class="input-group">
            {!! Form::text('date', null, ['placeholder' => trans('labels.date'), 'class' => 'form-control input-sm inputmask-birthday datepicker-birthday', 'required' => true]) !!}
            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>

        {!! $errors->first('date', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>