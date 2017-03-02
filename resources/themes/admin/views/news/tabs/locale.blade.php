<div class="form-group required @if ($errors->has($locale.'.name')) has-error @endif">
    {!! Form::label($locale . '[name]', trans('labels.name'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-7 col-md-10">
        {!! Form::text($locale.'[name]', isset($model->translate($locale)->name) ? $model->translate($locale)->name : '', ['placeholder'=> trans('labels.name'), 'required' => true, 'class' => 'form-control input-sm name_'.$locale]) !!}

        {!! $errors->first($locale.'.name', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has($locale . '[content]')) has-error @endif">
    {!! Form::label($locale . '[content]', trans('labels.content'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-8 col-sm-7 col-md-10">
        {!! Form::textarea($locale . '[content]', isset($model->translate($locale)->content) ? $model->translate($locale)->content : '', ['rows' => '3', 'placeholder' => trans('labels.content'), 'class' => 'form-control input-sm content_' . $locale]) !!}

        {!! $errors->first($locale . '[content]', '<p class="help-block error">:message</p>') !!}
    </div>
</div>
@include('partials.tabs.ckeditor', ['id' => $locale . '[content]'])