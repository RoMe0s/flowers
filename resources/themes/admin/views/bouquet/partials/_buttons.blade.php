<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif">
    <div class="col-md-3">
        <a href="{!! empty($back_url) ? route('admin.bouquet.index') : $back_url !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
    </div>

    @if ($user->hasAccess('bouquet.write') || $user->hasAccess('bouquet.create'))
        <div class="col-md-4 pull-right ta-right">
            {!! Form::submit(trans('labels.save'), array('class' => 'btn btn-success btn-flat')) !!}
        </div>
    @endif
</div>
