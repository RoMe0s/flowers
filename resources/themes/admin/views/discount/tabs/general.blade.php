<div class="form-group required @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', trans('labels.price'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('price', null, ['placeholder' => trans('labels.price'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('price', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('discount')) has-error @endif">
    {!! Form::label('discount', trans('labels.discount'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('discount', null, ['placeholder' => trans('labels.discount') .' '. trans('labels.percent'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('discount', '<p class="help-block error">:message</p>') !!}
    </div>
</div>