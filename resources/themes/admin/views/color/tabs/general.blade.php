<div class="form-group required @if ($errors->has('hex')) has-error @endif">
    {!! Form::label('hex', trans('labels.hex'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::color('hex', null, ['class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('hex', '<p class="help-block error">:message</p>') !!}
    </div>
</div>