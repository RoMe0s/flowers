@extends('layouts.main')

@section('content')
    <div classs="row">
        <div classs="col-sm-12">
            <div class="nav-tabs-custom">
                <div class="tab-content table-responsive">
                    <table class="table">
                        <thead class="order-status-{!! $model->status !!}">
                        <tr>
                            <th colspan="2">
                               <h4 class="text-center">{!! \App\Models\Order::getStatuses($model->status) !!}</h4>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <h4 class="text-center">Информация о заказе</h4>
                            </td>
                        </tr>
                            <tr>
                                <td>
                                    <strong class="pay-link">{!! route('order.pay', ['id' => $model->id]) !!}</strong>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-default btn-flat btn-sm col-sm-12 copy-button">
                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Адрес доставки
                                </td>
                                <td>
                                    {!! $model->getAddress() ? $model->getAddress() . (isset($model->address->code) ? ', код: ' . $model->address->code : "") : trans('labels.no') !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Дата и время доставки
                                </td>
                                <td>
                                    {!! $model->date ?: trans('labels.no') !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Текст открытки
                                </td>
                                <td>
                                    {!! $model->card_text ?: trans('labels.no') !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Доп. информация
                                </td>
                                <td>
                                    {!! $model->desc ?: trans('labels.no') !!}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h4 class="text-center">Контакты</h4>
                                </td>
                            </tr>
                        <tr>
                            <td>
                                Клинет
                            </td>
                            <td>
                                {!! $model->recipient_name !!}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Телефон
                            </td>
                            <td>
                                {!! $model->recipient_phone !!}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Email
                            </td>
                            <td>
                                {!! isset($model->user) ? $model->user->email : trans('labels.no') !!}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <table class="table">
                        <thead>
                        <tr>
                            <th colspan="6">
                                <h4 class="text-center">Заказанные товары</h4>
                            </th>
                        </tr>
                        <tr>
                            <th>

                            </th>
                            <th>
                                Название
                            </th>
                            <th>
                                Цена(руб.)
                            </th>
                            <th>
                                Скидка(руб.)
                            </th>
                            <th>
                                Кол-во
                            </th>
                            <th>
                                Стоимость(руб.)
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(sizeof($model->items))
                            @foreach($model->items as $item)
                                @include('order.partials.item', ['discount' => $model->discount, 'editable' => false])
                            @endforeach
                        </tbody>
                        @else
                            <tr>
                                <td colspan="6" class="text-center">
                                    @lang('labels.no')
                                </td>
                            </tr>
                        @endif
                    </table>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <h4 class="text-center">Сумма заказа</h4>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Скидка
                            </td>
                            <td>
                                {!! $model->discount !!} %
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Цена доставки
                            </td>
                            <td>
                                {!! $model->delivery_price !!} руб.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Итого
                            </td>
                            <td>
                                {!! $model->getTotal() !!} руб.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Предоплата
                            </td>
                            <td>
                                {!! $model->totalPrepay() !!} руб. ({!! $model->prepay !!}%)
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br />
                    <br />
                    <div class="col-sm-12">
                        <a style="width: 100%" class="btn btn-primary btn-flat btn-sm" href="{!! route('admin.order.edit', ['id' => $model->id]) !!}">Редактировать</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
