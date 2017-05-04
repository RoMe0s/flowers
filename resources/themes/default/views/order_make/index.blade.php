@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="doc">
        </h2>

        {!! $model->content !!}

        <div class="row">
            <div class="col-lg-8 col-sm-8 col-xs-12">
                <form action="" method="post">
                    {{ csrf_field() }}

                    @include('errors.form')

                    <h4><span class="text-danger">*</span>Выберите адрес доставки</h4>
                    <div>
                        @foreach($addresses as $address)
                            <input type="radio" name="address_id" value="{{ $address->id }}" onclick="$('.address').hide()" {{ (old('address_id') == $address->id)? 'checked': '' }} required>
                            {{ $address->address }}<br>
                        @endforeach

                        <input type="radio" name="address_id" value="0" onclick="$('.address').show()" {{ (old('address') == 0 || $addresses->count() == 0)? 'checked' : '' }}>
                        Другой адрес

                        <br>

                        <div class="address" style="display: {{ (old('address') == 0 || $addresses->count() == 0)? 'block': 'none' }};">
                            <br>

                            <p>
                                Город <span class="text-danger">*</span>
                                <input class="form-control input-sm" type="text" name="city" value="{{ old('city') }}">
                            </p>
                            <p>
                                Улица <span class="text-danger">*</span>
                                <input class="form-control input-sm" type="text" name="street" value="{{ old('street') }}">
                            </p>
                            <p>
                                Дом <span class="text-danger">*</span>
                                <input class="form-control input-sm" type="text" name="house" value="{{ old('house') }}">
                            </p>
                            <p>
                                Квартира <span class="text-danger">*</span>
                                <input class="form-control input-sm" type="text" name="flat" value="{{ old('flat') }}">
                            </p>
                            <p>
                                Код домофона
                                <input class="form-control input-sm" type="text" name="code" value="{{ old('code') }}">
                            </p>
                        </div>
                    </div>

                    <br>

                    <h4><span class="text-danger">*</span>Дата и время доставки</h4>
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <p>
                                <input class="form-control input-sm" name="date" value="{{ old('date') }}" required>
                            </p>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <p>
                                {!! Form::select('time', $times, null, array('class' => 'form-control input-sm', 'required' => true)) !!}
                            </p>
                        </div>
                    </div>

                    <br>

                    <h4><span class="text-danger">*</span>Для кого?</h4>
                    <p>
                        <input type="radio" name="prepay" value="50" onclick="$('.contacts').hide();" {{ (old('prepay') == 50)? 'checked': ((old('prepay') != 100)? 'checked': '') }} required> Для себя (предоплата 50%)<br>
                        <input type="radio" name="prepay" value="100" onclick="$('.contacts').show();" {{ (old('prepay') == 100)? 'checked': '' }} required> В подарок (предоплата 100%)
                    </p>

                    <div style="display: {{ (old('prepay') == 100)? 'block': 'none' }};" class="row contacts">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <input class="form-control input-sm" type="text" name="recipient_name" value="{{ old('recipient_name') }}" placeholder="ФИО получателя">
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <input class="form-control input-sm" type="text" name="recipient_phone" value="{{ old('recipient_phone') }}" placeholder="Телефон получателя">
                        </div>
                    </div>

                    <br>

                    <h4>Текст открытки <span class="text-muted">максимум 150 символов</span></h4>
                    <p>
                        <textarea class="form-control input-sm" name="card_text" maxlength="150" rows="5">{{ old('card_text') }}</textarea>
                    </p>

                    <br>

                    <h4>Комментарии</h4>
                    <p>
                        <textarea class="form-control input-sm" name="desc" maxlength="255" rows="5">{{ old('desc') }}</textarea>
                    </p>

                    <br>

                    <h4>Соглашение</h4>
                    <p>
                        <input type="checkbox" name="agreement" value="1" required> Я ознакомился(-лась) и согласен(-сна) с <a href="{{ url('/offer') }}" target="_blank">условиями Публичной ОФЕРТЫ</a>.
                    </p>

                    <p class="text-right">
                        <input class="btn btn-purple" type="submit" value="Продолжить" onclick="yaCounter29938144.reachGoal('addOrder'); return true;">
                    </p>
                </form>
            </div>
            <div class="col-lg-4 col-sm-4 col-xs-12">
                <div style="background: #f0f0f0; padding: 5px;">
                    <h4 class="text-center">Сумма заказа</h4>

                    <br>

                    <table class="table">
                        <tr>
                            <td>Сумма без скидки:</td>
                            <td>{{ Cart::total() }} руб</td>
                        </tr>
                        <tr>
                            <td>Скидка:</td>
                            <td>{{ Cart::discount() }} руб ({{ \App\Http\Controllers\Frontend\CartController::_cartDiscount(true) }}%)</td>
                        </tr>
                        <tr>
                            <td><b>ИТОГО:</b></td>
                            <td>{{ Cart::subtotal() }} руб</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
    <script>
        $(document).ready(function(){
            $.datetimepicker.setLocale('ru');
            $('input[name="date"]').datetimepicker({
                timepicker: false,
                format: 'd-m-Y'
            });
        });
    </script>
@endsection

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" />
@endsection