{!! Form::open(array("route" => array("admin." . $type . ".destroy", $model->id), "method" => "delete", 'class' => 'pull-left')) !!}

    @if ($user->hasAccess((isset($access) ? $access : $type).'.read'))
        <a class="btn btn-info btn-sm btn-flat" href="{!! route('admin.' . $type . '.edit', array($model->id)) !!}"
           title="{!! trans('labels.edit') !!}">
            <i class="fa fa-pencil"></i>
        </a>&nbsp;
    @endif

@if(!$model instanceof \App\Models\Order)
    @if($model instanceof \App\Models\Page && !is_system_page($model->slug) || !$model instanceof \App\Models\Page)
        @if ($user->hasAccess((isset($access) ? $access : $type).'.delete'))
            <a class="btn btn-danger btn-sm btn-flat" href="javascript:void(0);" title="{!! trans('labels.delete') !!}"
               onclick="return dialog('{!! trans('labels.deleting_record') !!}', '{!! trans('messages.delete_record') !!}',  $(this).closest('form'));">
                <i class="fa fa-trash"></i>
            </a>&nbsp;
        @endif
    @endif
@endif

@if($model instanceof \App\Models\Page && !is_system_page($model->slug) || !$model instanceof \App\Models\Page)
    @if (isset($front_link) && $front_link === true )
        <a class="btn btn-primary btn-sm btn-flat" href="{!! $model->getUrl() !!}" title="@lang('labels.go_to_front')" target="_blank">
            <i class="fa fa-external-link"></i>
        </a>
    @endif
@endif

    @if (isset($basket_link) && $basket_link === true )
        <a class="btn btn-primary btn-sm btn-flat add_to_basket" href="#" title="@lang('labels.add_to_basket')" data-id="{!! $model->id !!}" data-type="{!! class_basename($model) !!}">
            <i class="fa fa-plus"></i>
        </a>
    @endif

{!! Form::close() !!}