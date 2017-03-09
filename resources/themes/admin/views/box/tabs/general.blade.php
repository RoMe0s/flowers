<div class="form-group required @if ($errors->has('width')) has-error @endif">
    {!! Form::label('width', trans('labels.width'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('width', null, ['placeholder' => trans('labels.width'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('width', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('length')) has-error @endif">
    {!! Form::label('length', trans('labels.length'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::text('length', null, ['placeholder' => trans('labels.length'), 'class' => 'form-control input-sm', 'required' => true]) !!}

        {!! $errors->first('length', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('category_id')) has-error @endif">
    {!! Form::label('category', trans('labels.category'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('category_id', $categories, null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true)) !!}

        {!! $errors->first('category_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::imageInput('image', $model->image) !!}

        {!! $errors->first('image', '<p class="help-block error">:message</p>') !!}
    </div>
</div>