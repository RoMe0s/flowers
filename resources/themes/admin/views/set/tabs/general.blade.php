<div class="form-group required @if ($errors->has('slug')) has-error @endif">
    {!! Form::label('slug', trans('labels.slug'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::text('slug', null, ['placeholder' => trans('labels.slug'), 'required' => true, 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('slug', '<p class="help-block error">:message</p>') !!}
    </div>

    <a href="#" class="btn btn-success btn-flat btn-xs margin-top-4 slug-generate">{!! trans('labels.generate') !!}</a>
</div>

<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::imageInput('image', $model->image) !!}

        {!! $errors->first('image', '<p class="help-block error">:message</p>') !!}
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
        {!! Form::text('position', $model->position ?: 0 , ['required' => true, 'placeholder' => trans('labels.position'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', trans('labels.price'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('price', null , ['required' => true, 'placeholder' => trans('labels.price'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('price', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('count')) has-error @endif">
    {!! Form::label('count', trans('labels.count'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('count', null , ['placeholder' => trans('labels.count'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('count', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('flowers')) has-error @endif">
    {!! Form::label('flower', trans('labels.flower'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('flowers[]', $flowers, $model->flowers()->lists('flower_id')->toArray(), array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'multiple' => true, 'required' => true)) !!}

        {!! $errors->first('flowers', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('box')) has-error @endif">
    {!! Form::label('box', trans('labels.box'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('box_id', $boxes, null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true)) !!}

        {!! $errors->first('box', '<p class="help-block error">:message</p>') !!}
    </div>
</div>