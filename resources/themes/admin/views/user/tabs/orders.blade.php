<div class="box-body">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="col-sm-2 text-center">
                            №
                        </th>
                        <th class="col-sm-5 text-center">
                            Статус
                        </th>
                        <th class="col-sm-5 text-center">
                            Сумма
                        </th>
                    </tr>
                </thead>
                @php($total_by_user = 0)
                @foreach($model->orders as $key => $order)
                    <tr class="order-status-{!! $order->status !!}">
                        <td class="text-center">
                            <a target="_blank" href="{!! route('admin.order.edit', ['id' => $order->id]) !!}"> Заказ #{!! $order->id !!} </a>
                        </td>
                        <td class="text-center">
                            {!! \App\Models\Order::getStatuses($order->status) !!}
                        </td>
                        <td class="text-center">
                            {!! $order->getTotal() !!} руб.
                        </td>
                    </tr>
                    @php($total_by_user += $order->getTotal())
                @endforeach
                @if(!sizeof($model->orders))
                    <tr>
                        <td colspan="3" class="text-center">
                            <h3>
                                Заказов нет
                            </h3>
                        </td>
                    </tr>
                @endif
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-right">
                            Итого:
                        </td>
                        <td class="text-left">
                            {!! $total_by_user !!} руб.
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>