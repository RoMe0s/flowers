<div class="form-group required @if ($errors->has('layout_position')) has-error @endif">
    {!! Form::label('layout_position', trans('labels.layout_position'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('layout_position', null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.layout_position')]) !!}

        {!! $errors->first('layout_position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('template')) has-error @endif">
    {!! Form::label('template', trans('labels.template'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('template', $templates, null, ['class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true]) !!}

        {!! $errors->first('template', '<p class="help-block error">:message</p>') !!}
    </div>
</div>