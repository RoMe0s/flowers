<div class="form-group required @if ($errors->has('slug')) has-error @endif">
    {!! Form::label('slug', trans('labels.slug'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::text('slug', null, ['placeholder' => trans('labels.slug'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('slug', '<p class="help-block error">:message</p>') !!}
    </div>

    <a href="#" class="btn btn-success btn-flat btn-xs margin-top-4 slug-generate" data-from_id=".title_{!! Lang::getLocale() !!}">{!! trans('labels.generate') !!}</a>
</div>

<div class="form-group required @if ($errors->has('type')) has-error @endif">
    {!! Form::label('type', trans('labels.type'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('type', $types, null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('type', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('value')) has-error @endif">
    {!! Form::label('value', trans('labels.value'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('value', null , ['placeholder' => trans('labels.value'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('value', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('position')) has-error @endif">
    {!! Form::label('position', trans('labels.position'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('position', $model->position ?: 0 , ['placeholder' => trans('labels.position'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
