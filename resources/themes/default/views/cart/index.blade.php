@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="bag">
        </h2>

        {!! $model->content !!}

        @include('errors.form')

        <div class="panel panel-default">
            @if(Cart::count() == 0)
                <div class="panel-body text-center">Пусто</div>
            @else
                <table class="table cart">
                    <thead>
                    <th></th>
                    <th></th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Скидка</th>
                    <th>Кол-во</th>
                    <th>Сумма</th>
                    </thead>
                    <tbody>
                    @foreach(Cart::content() as $item)
                        <tr>
                            <td>
                                <a class="btn btn-default btn-sm" href="/cart/box/{{ $item->rowid }}/remove">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                            <td>
                                <div class="photo photo-small" style="width: 150px; background-image: url('{{ $item->options['image'] }}')"></div>
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->price }} руб</td>
                            <td>{{ $item->discount }} руб</td>
                            <td>
                                <div class="btn-group-xs">
                                    @if($item->qty != 1)
                                        <a class="btn btn-default" href="/cart/{{ $item->rowid }}/qty/minus">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    @endif

                                    <button class="btn btn-default" type="button" disabled>
                                        {{ $item->qty }}
                                    </button>
                                    <a class="btn btn-default" href="/cart/{{ $item->rowid }}/qty/plus">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                </div>
                            </td>
                            <td>{{ $item->subtotal }} руб</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if(Cart::count() != 0)
            <div class="panel panel-default">
                <div class="panel-heading">Итог</div>

                <table class="table cart">
                    <tfoot>
                    <tr>
                        <td>Сумма без скидки</td>
                        <td>{{ Cart::total() }} руб</td>
                    </tr>
                    <tr>
                        <td>Скидка</td>
                        <td>{{ Cart::discount() }} руб ({!! \App\Http\Controllers\Frontend\CartController::_cartDiscount(true) !!}%) </td>
                    </tr>
                    <tr>
                        <td><b>ИТОГО</b></td>
                        <td>{{ Cart::subtotal() }} руб</td>
                    </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <form action="/cart/apply/code" method="post">
                        {!! csrf_field() !!}

                        <p>
                            <label class="input-group">
                                <input class="form-control" type="text" name="code" placeholder="Введите промо-код" required>
                                <span class="input-group-btn">
                                    <input class="btn btn-danger" type="submit" value="Использовать">
                                </span>
                            </label>
                        </p>
                    </form>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12">
                    <p class="text-right">
                        <a class="btn btn-default" href="/cart/clear">Очистить</a>
                        <a class="btn btn-danger" href="/cart/make/order">Оформить</a>
                    </p>
                </div>
            </div>
        @endif
    </section>

    @if(sizeof(Cart::count()))
        @widget__related_products(4)
    @endif
@endsection