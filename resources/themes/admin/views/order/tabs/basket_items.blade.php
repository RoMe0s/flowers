<div class="box-body table-responsive no-padding">
    <table class="table table-hover duplication">
        <tbody>
        @if (count($items))
            <tr>
                <th>{!! trans('labels.image') !!}</th>
                <th>{!! trans('labels.name') !!}</th>
                <th>{!! trans('labels.price') .'('.trans('labels.rub').')' !!}</th>
                <th>{!! trans('labels.discount').'('.trans('labels.rub').')' !!}</th>
                <th>{!! trans('labels.count') !!}</th>
                <th>{!! trans('labels.total').'('.trans('labels.rub').')' !!}</th>
                <th></th>
            </tr>
            @foreach($items as $parent_item)
                @foreach($parent_item as $item)
                    <?php
                        if($item instanceof \App\Models\OrderItem) {
                            $item = $item->itemable;
                            $item->basket_count = $item->count;
                        }
                        if($item instanceof \App\Models\Individual) {
                            $name = trans('labels.individual product') . ' #' . $item->id;
                        } elseif($item instanceof \App\Models\Sale) {
                            $name = trans('labels.sale product') . ' #' . $item->id;
                        } else {
                            $name = $item->name;
                        }
                        $discount = 0;
                    ?>
                    {!! Form::hidden('items[new]['.$item->id.'][itemable_type]', "App\\Models\\" . class_basename($item)) !!}
                    {!! Form::hidden('items[new]['.$item->id.'][itemable_id]', $item->id) !!}
                    {!! Form::hidden('items[new]['.$item->id.'][price]', $item->price) !!}
                    {!! Form::hidden('items[new]['.$item->id.'][count]', $item->basket_count) !!}
                    <tr class="duplication-row">
                        <td>
                            <div image="form-group required @if ($errors->has('items.' .$item->image. '.image')) has-error @endif">
                               <img src="{!! $item->image ?: 'http://www.placehold.it/50x50/EFEFEF/AAAAAA&text=no+image' !!}" style="max-width: 50px; max-height: 50px;"/>
                            </div>
                        </td>
                        <td>
                            <div name="form-group required @if ($errors->has('items.' .$item->name. '.name')) has-error @endif">
                                {!! Form::text(null, $name , ['class' => 'form-control input-sm', 'required' => true, 'disabled' => true]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('items.' .$item->id. '.price')) has-error @endif">
                                {!! Form::text(null, $item->price, ['class' => 'form-control input-sm', 'disabled' => true]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('items.' .$item->id. '.discount')) has-error @endif">
                                {!! Form::text(null, $discount, ['id' => 'items.' .$item->id. '.discount', 'class' => 'form-control input-sm', 'disabled' => true]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('items.' .$item->id. '.count')) has-error @endif">
                                {!! Form::text(null, $item->basket_count, ['class' => 'form-control input-sm', 'disabled' => true]) !!}
                            </div>
                        </td>
                        <td>
                            <div class="form-group required @if ($errors->has('items.' .$item->id. '.total')) has-error @endif">
                                {!! Form::text(null, $item->basket_count * ($item->price - ($item->price * $discount) ), ['class' => 'form-control input-sm', 'disabled' => true]) !!}
                            </div>
                        </td>
                        <td class="coll-actions">
                            <a data-id="{!! $item->id !!}" data-type="{!! class_basename($item) !!}" class="btn btn-flat btn-danger btn-xs destroy remove_from_basket" data-id="{!! $item->id !!}"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        @else
        <tr>
            <h4 class="text-center">@lang('labels.no items in the basket')</h4>
        </tr>
        @endif

        </tbody>
    </table>
</div>