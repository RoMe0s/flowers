<div class="form-group required @if ($errors->has('menuable_type')) has-error @endif">
    {!! Form::label('menuable_type', trans('labels.type'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('menuable_type', $types, null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.type'))) !!}

        {!! $errors->first('menuable_type', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

@include('mainpagemenu.partials.menuable_id')

<div class="form-group required @if ($errors->has('status')) has-error @endif">
    {!! Form::label('status', trans('labels.status'), array('class' => 'control-label col-xs-4 col-sm-3 col-md-2')) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::select('status', ['1' => trans('labels.status_on'), '0' => trans('labels.status_off')], null, array('class' => 'form-control select2 input-sm', 'aria-hidden' => 'true')) !!}

        {!! $errors->first('status', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<div class="form-group @if ($errors->has('position')) has-error @endif">
    {!! Form::label('position', trans('labels.position'), ['class' => 'control-label col-xs-4 col-sm-3 col-md-2']) !!}

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
        {!! Form::text('position', $model->position ?: 0 , ['placeholder' => trans('labels.position'), 'class' => 'form-control input-sm']) !!}

        {!! $errors->first('position', '<p class="help-block error">:message</p>') !!}
    </div>
</div>

<script>
    $(document).ready(function () {

        $('select[name="menuable_type"]').on("change", function () {

            var $this = $(this),
                $values = $(document).find('select[name="menuable_id"]'),
                val = $this.val();

            if(val.length) {

                $.ajax({

                    url: '/admin/mainpagemenu/load',
                    type: 'GET',
                    data: {
                        type: val
                    }

                }).done(function(response) {

                    $values.closest('.form-group')[0].outerHTML = response.html;

                    $(document).find('select[name="menuable_id"]').select2(window.select2Options);

                }).error(function() {

                    $values.find('option').remove();

                });

            } else {

                $values.find('option').remove();

            }

        });

    });
</script>