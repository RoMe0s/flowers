@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="bag">
        </h2>

        {!! $model->content !!}

        @include('errors.form')

        <div class="panel panel-default table-responsive">
            @if(Cart::count() == 0)
                <div class="panel-body text-center">Пусто</div>
            @else
                <table class="table cart">
                    <thead>
                    <th class="col-sm-1"></th>
                    <th class="col-sm-1"></th>
                    <th class="col-sm-2">Название</th>
                    <th class="col-sm-2">Цена</th>
                    <th class="col-sm-2">Скидка</th>
                    <th class="col-sm-2">Кол-во</th>
                    <th class="col-sm-2">Сумма</th>
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
                                <div class="photo photo-small" style="width: 150px; background-image: url('{{ $item->options['image'] ? create_thumbnail($item->options['image'], 150) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=150&w=150" }}')"></div>
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->price }} руб</td>
                            <td>{{ $item->discount }} руб</td>
                            <td>
                                <div class="btn-group-sm card-qty">
                                    @if($item->qty != 1)
                                        <a class="btn btn-default" href="/cart/{{ $item->rowid }}/qty/minus">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-default" disabled="disabled">
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