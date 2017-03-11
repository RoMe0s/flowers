<?php
if($item->itemable instanceof \App\Models\Individual) {
    $name = 'Инд. товар #' . $item->id;
} elseif($item->itemable instanceof \App\Models\Sale) {
    $name = 'Акция #' . $item->id;
} else {
    $name = $item->itemable->name;
}
?>
<tr class="duplication-row row-id-{!! $item->id !!}">
    <td>
        <div image="form-group required @if ($errors->has('items.' .$item->image. '.image')) has-error @endif">
            <img src="{!! isset($item->itemable->image) && $item->itemable->image != "" ? $item->itemable->image : 'http://www.placehold.it/50x50/EFEFEF/AAAAAA&text=no+image' !!}" style="max-width: 50px; max-height: 50px;"/>
        </div>
    </td>
    <td>
        <div class="form-group @if ($errors->has('items.old.' .$item->id. '.class')) has-error @endif">
            {!! Form::text(null, $name, ['class' => 'form-control input-sm', 'disabled' => true]) !!}
        </div>
    </td>
    <td>
        <div class="form-group @if ($errors->has('items.old.' .$item->id. '.price')) has-error @endif">
            {!! Form::text('items[old][' . $item->id .'][price]', $item->price, ['class' => 'form-control input-sm', 'disabled' => true]) !!}
        </div>
    </td>
    <td>
        <div class="form-group required @if ($errors->has('items.old.' .$item->id. '.count')) has-error @endif">
            {!! Form::text(null, ($item->price * ($discount / 100)), ['class' => 'form-control input-sm', 'disabled' => true]) !!}
        </div>
    </td>
    <td>
        <div class="form-group required @if ($errors->has('items.old.' .$item->id. '.count')) has-error @endif">
            {!! Form::text('items[old][' .$item->id. '][count]', $item->count, ['class' => 'form-control input-sm', 'disabled' => true]) !!}
        </div>
    </td>
    <td>
        <div class="form-group required @if ($errors->has('items.' .$item->id. '.total')) has-error @endif">
            {!! Form::text(null, $item->count * ($item->price - ($item->price * ($discount / 100)) ), ['class' => 'form-control input-sm', 'disabled' => true]) !!}
        </div>
    </td>
    <td class="coll-actions">
        <a class="btn btn-flat btn-danger btn-xs action exist destroy" data-id="{!! $item->id !!}" data-name="items[remove][]"><i class="fa fa-remove"></i></a>
    </td>
</tr>