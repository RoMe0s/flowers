<div class="row box-footer @if (!empty($class)) {!! $class !!} @endif">
    <div class="col-md-3">
        <a href="{!! empty($back_url) ? route('admin.order.index') : $back_url !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
    </div>

    @if ($user->hasAccess('order.write') || $user->hasAccess('order.create'))
        <div class="col-md-4 pull-right ta-right">
            {!! Form::submit(trans('labels.save'), array('class' => 'btn btn-success btn-flat')) !!}
        </div>
        @if(isset($model->id) && !isset($class) && strlen($baskets))
            <div class="pull-right col-md-2">
                <a class="btn btn-info btn-flat btn-sm add-basket-item-to-order">@lang('labels.add')</a>
            </div>
            <div class="pull-right col-md-3 order-basket-items">
                <select class="form-control select2 input-sm">
                    <option value="-1">@lang('labels.basket item')</option>
                    {!! $baskets !!}
                </select>
            </div>
        @endif
    @endif
</div>
