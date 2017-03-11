@extends('profile.index')

@section('profile-content')
    @if(!sizeof($subscriptions))
        <p class="text-center">
            <i>Подписок нет</i>
        </p>
    @else
        <br>

        @foreach($subscriptions as $subscription)
            <div class="subscription">
                <div class="title">{{ $subscription->title }}</div>
                <div class="desc">{!! $subscription->content  !!}</div>

                @if($subscription->isPaid())
                    <div class="status paid text-right">Оплачена до {{ $subscription->getPaidBefore() }}</div>
                @else
                    <div class="status not-paid text-right">
                        <a class="btn btn-purple-outline" href="/subscription/{{ $subscription->id }}/pay">Оплатить</a>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
@endsection