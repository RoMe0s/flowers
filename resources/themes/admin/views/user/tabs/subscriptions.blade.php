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
                @foreach($model->subscriptions as $key => $subscription)
                    <tr class="order-status-{!! $subscription->isPaid() ? 5 : 0  !!}">
                        <td class="text-center">
                            <a target="_blank" href="{!! route('admin.subscription.edit', ['id' => $subscription->id]) !!}"> Подписка #{!! $subscription->id !!} </a>
                        </td>
                        <td class="text-center">
                            {!! $subscription->isPaid() ? 'Оплачено до ' . $subscription->getPaidBefore() : 'Не оплачено' !!}
                        </td>
                        <td class="text-center">
                            {!! $subscription->price !!} руб.
                        </td>
                    </tr>
                    @php($total_by_user += $subscription->price)
                @endforeach
                @if(!sizeof($model->subscriptions))
                    <tr>
                        <td class="text-center" colspan="3">
                            <h3>
                                Подписок нет
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