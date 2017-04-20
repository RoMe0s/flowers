<div class="form-group required @if ($errors->has('productable_id')) has-error @endif">
    {!! Form::label('productable_id', trans('labels.name'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('productable_id', $productables, null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.name'))) !!}

        {!! $errors->first('productable_id', '<p class="help-block error">:message</p>') !!}
    </div>
</div>