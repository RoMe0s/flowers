<div class="form-group required @if ($errors->has('colors')) has-error @endif">
    {!! Form::label('colors', trans('labels.colors'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('colors[]', $colors, $model->colors->lists('id')->toArray() , array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true, 'multiple' => true)) !!}

        {!! $errors->first('colors', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>