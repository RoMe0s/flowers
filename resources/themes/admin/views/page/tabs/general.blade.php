<div class="form-group required @if ($errors->has('slug')) has-error @endif">
    {!! Form::label('slug', trans('labels.slug'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::text('slug', null, ['placeholder' => trans('labels.slug'), 'required' => true, 'class' => 'form-control input-sm', 'disabled' => !is_system_page($model->slug) ? false : true]) !!}

        {!! $errors->first('slug', '<p class="help-block error">:message</p>') !!}
    </div>
    @if(!is_system_page($model->slug))
        <a href="#" class="btn btn-success btn-flat btn-xs margin-top-4 slug-generate">{!! trans('labels.generate') !!}</a>
    @endif
</div>

<div class="form-group required @if ($errors->has('template')) has-error @endif">
    {!! Form::label('template', trans('labels.template'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('template', $templates, null, array('required' => true, 'class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'placeholder' => trans('labels.template'))) !!}

        {!! $errors->first('template', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('parent_id')) has-error @endif">
    {!! Form::label('parent_id', trans('labels.page_parent_id'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('parent_id', $parents, null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('parent_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('image')) has-error @endif">
    {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-4">
        {!! Form::imageInput('image', $model->image) !!}

        {!! $errors->first('image', '<p class="help-block error">:message</p>') !!}
    </div>
</div>