@extends('layouts.main')

@section('content')
    <div classs="row">
        <div classs="box-content col-md-12">
            <table classs="table table-responsive">
                <thead>
                <tr>
                    <th colspan="2">
                        Информация о заказе
                    </th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Адрес доставки
                        </td>
                        <td>
                            {!! $model->address_id !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Дата и время доставки
                        </td>
                        <td>
                            {!! $model->date !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Текст открытки
                        </td>
                        <td>
                            {!! $model->card_text !!}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Доп. информация
                        </td>
                        <td>
                            {!! $model->desc !!}
                        </td>
                    </tr>    
                </tbody>
            </div>
        </div>
    </div>
@endsection
