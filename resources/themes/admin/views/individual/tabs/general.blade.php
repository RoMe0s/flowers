<div class="form-group required @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', trans('labels.email'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('email', null, ['placeholder' => trans('labels.email'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('email', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('phone')) has-error @endif">
    {!! Form::label('phone', trans('labels.phone'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('phone', null, ['placeholder' => trans('labels.phone'), 'required' => true, 'class' => 'form-control input-sm inputmask-2']) !!}

        {!! $errors->first('phone', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', trans('labels.price'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('price', $model->price ?: 0, ['placeholder' => trans('labels.price'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('price', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::imageInput('image', $model->image) !!}

        {!! $errors->first('image', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('text')) has-error @endif">
    {!! Form::label('text', trans('labels.text'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
            {!! Form::textarea('text', null, ['placeholder' => trans('labels.text'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('text', '<p class="help-block error">:message</p>') !!}
    </div>
</div>