@extends('profile.index')

@section('profile-content')
    @if(isset($discounts) && sizeof($discounts))
        <p class="text-center">Покупайте больше, тратьте меньше!</p>
        <br>
        <table class="table discounts">
            <thead>
            <th>Сумма заказов</th>
            <th>Скидка</th>
            </thead>
            <tbody>
            @foreach($discounts as $discount)
                <tr>
                    <td>от {{ $discount['price'] }} рублей</td>
                    <td>{{ $discount['discount'] }}%</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            @if($user->hasAccess('vip'))
                <tr>
                    <td>Ваша постоянная скидка (только для VIP-клиентов):</td>
                    <td>{{ $user->start_discount }}%</td>
                </tr>
            @endif

            <tr>
                <td>Ваша накопительная скидка:</td>
                <td>{{ $user->discount }}%</td>
            </tr>
            <tr>
                <td>Итого:</td>
                <td>{!! $user->getDiscount() !!}%</td>
            </tr>
            </tfoot>
        </table>
    @else
        <p class="text-center">
            <i>Раздел пуст</i>
        </p>
    @endif
@endsection