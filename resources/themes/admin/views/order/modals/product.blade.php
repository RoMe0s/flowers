
<table class="table">
    <thead>
    <tr>
        <th class="col-xs-2">ID</th>
        <th class="col-xs-4">Название</th>
        <th class="col-xs-4">Цена</th>
        <th class="col-xs-2">Количество</th>
        <th class="col-xs-1"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
    <tr>
        <td>
            {!! $product->id . '(' . $names[class_basename($product)] . ')' !!}
        </td>
        <td>
            {{ isset($product->title) ? $product->title : $product->name }}
        </td>
        <td>
            {!! $product->price !!}
        </td>
        <td>
            {!! Form::text(null, 1, array('class' => 'form-control input-sm product-count')) !!}
        </td>
        <td>
            <a class="btn btn-success btn-sm btn-flat add-product"
               data-name="{{ isset($product->title) ? $product->title : $product->name }}"
               data-price="{!! $product->price !!}"
               data-id="{!! $product->id !!}"
               data-type="{!! "App\\Models\\" . class_basename($product) !!}"
               data-show_id_type="{!! $product->id . '(' . $names[class_basename($product)] . ')' !!}"
            >Добавить</a>
        </td>
    </tr>
    @endforeach
    @if(!sizeof($products))
        <tr>
            <td colspan="5">
                <h4 class="text-center">
                    Ничего не нашлось
                </h4>
            </td>
        </tr>
    @endif
    </tbody>
</table>
