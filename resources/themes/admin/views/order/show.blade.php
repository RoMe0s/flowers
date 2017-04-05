@extends('layouts.editable')

@section('content')
    <style>
        .order-show {
            font-size: 13px;
            line-height: 1.6;
        }
        .order-show h4 {
            padding: 10px 0;
            margin: 0;
        }
        .order-show textarea {
            resize: vertical;
        }
        @media screen and (max-width: 540px) {

            .order-show .m-marg {
                margin-bottom: 10px;
                border-bottom: 1px solid #eaeaea;
            }

        }
    </style>
    <div class="row">
        <div class="col-sm-12">
            <div class="nav-tabs-custom">
                <div class="tab-content col-sm-12 order-show">
                        <h4 class="order-status-{!! $model->status !!} text-center">
                            {!! \App\Models\Order::getStatuses($model->status) !!}
                        </h4>
                        <h4 class="text-center col-sm-12">Информация о заказе</h4>
                                <div class="col-sm-12 col-md-6">
                                    <strong class="pay-link">{!! route('order.pay', ['id' => $model->id]) !!}</strong>
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    <a href="#" class="btn btn-default btn-flat btn-sm col-sm-12 copy-button" style="width: 100%; margin-bottom: 15px">
                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    Доп. информация
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    {!! $model->desc ?: trans('labels.no') !!}
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    Адрес доставки
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    {!! $model->getAddress() ? $model->getAddress() . (isset($model->address->code) ? ', код: ' . $model->address->code : "") : trans('labels.no') !!}
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    Дата доставки
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    {!! $model->date ?: trans('labels.no') !!}
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    Время доставки
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    {!! isset($times[$model->time]) ? $times[$model->time] : trans('labels.no') !!}
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    Текст открытки
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    @if($model->card_text)
                                        <textarea readonly class="col-sm-12" style="width: 100%;">{!! $model->card_text !!}</textarea>
                                    @else
                                        @lang('labels.no')
                                    @endif
                                </div>
                            <h4 class="text-center col-sm-12">Контакты</h4>
                            @if($model->recipient_name || $model->recipient_phone)
                                <div class="col-sm-12 col-md-6">
                                    <b>Получатель</b>
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    {!! $model->recipient_name ?: trans('labels.no' ) !!}
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <b>Телефон получателя</b>
                                </div>
                                <div class="col-sm-12 col-md-6 m-marg">
                                    {!! $model->recipient_phone ?: trans('labels.no' ) !!}
                                </div>
                            @endif
                            <div class="col-sm-12 col-md-6">
                                Клинет
                            </div>
                            <div class="col-sm-12 col-md-6 m-marg">
                                {!! $model->user->name ?: trans('labels.no') !!}
                            </div>

                            <div class="col-sm-12 col-md-6">
                                Телефон
                            </div>
                            <div class="col-sm-12 col-md-6 m-marg">
                                {!! $model->user->phone ?: trans('labels.no') !!}
                            </div>

                            <div class="col-sm-12 col-md-6">
                                Email
                            </div>
                            <div class="col-sm-12 col-md-6 m-marg">
                                {!! $model->user->email ?: trans('labels.no') !!}
                            </div>

                        <div class="clearfix"></div>

                        <div class="table-responsive col-sm-12">

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

                        </div>

                    <div class="clearfix"></div>

                    <h4 class="text-center">Сумма заказа</h4>
                            <div class="col-sm-12 col-md-6">
                                Скидка
                            </div>
                            <div class="col-sm-12 col-md-6 m-marg">
                                {!! $model->discount !!} %
                            </div>
                            <div class="col-sm-12 col-md-6">
                                Цена доставки
                            </div>
                            <div class="col-sm-12 col-md-6 m-marg">
                                {!! $model->delivery_price !!} руб.
                            </div>
                            <div class="col-sm-12 col-md-6">
                                Итого
                            </div>
                            <div class="col-sm-12 col-md-6 m-marg">
                                {!! $model->getTotal() !!} руб.
                            </div>
                            <div class="col-sm-12 col-md-6">
                                Предоплата
                            </div>
                            <div class="col-sm-12 col-md-6 m-marg">
                                {!! $model->totalPrepay() !!} руб. ({!! $model->prepay !!}%)
                            </div>
                    <div class="clearfix"></div>
                    <br/>
                <div class="col-sm-12">
                    <a style="width: 100%" class="btn btn-primary btn-flat btn-sm" href="{!! route('admin.order.edit', ['id' => $model->id]) !!}">Редактировать</a>
                </div>
                <div class="clearfix"></div>
        </div>
    </div>
</div>
@endsection
