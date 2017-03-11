<div class="box-body table-responsive no-padding">
    <table class="table table-hover duplication">
        <tbody>
            <tr>
                <th>{!! trans('labels.image') !!}</th>
                <th>{!! trans('labels.name') !!}</th>
                <th>{!! trans('labels.price') .'('.trans('labels.rub').')' !!}</th>
                <th>{!! trans('labels.discount').'('.trans('labels.rub').')' !!}</th>
                <th>{!! trans('labels.count') !!}</th>
                <th>{!! trans('labels.total').'('.trans('labels.rub').')' !!}</th>
                <th></th>
            </tr>

        @if (count($model->items))
            @foreach($model->items as $item)
                @include('order.partials.item')
            @endforeach
        @endif
        </tbody>
        @if(sizeof($model->items))
            <tfoot>
            <tr>
                <td colspan="5" class="text-right"><strong>Итого:</strong></td>
                <td colspan="2">{!! $model->getTotal() !!} руб.</td>
            </tr>
            </tfoot>
        @endif
    </table>
</div>