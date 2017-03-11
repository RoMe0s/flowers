@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="sub">
        </h2>

        {!! $model->content !!}

        @if(!sizeof($subscriptions))
            <p class="text-center">
                <i>Подписок нет</i>
            </p>
        @else
            @foreach($subscriptions as $subscription)
                <div class="subscription">
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="photo photo-small" style="background: url('{{ $subscription->image }}')"></div>
                        </div>
                        <div class="col-md-9 col-sm-8 col-xs-12">
                            <div class="title">{{ $subscription->title }}</div>
                            <div class="desc">{!! $subscription->content !!}</div>
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="price">{{ $subscription->price }} <small>руб. / мес.</small></div>
                                </div>
                                <div class="col-sm-6 col-xs-12 text-right">
                                    @if($user && $subscription->hasSubscriber($user->id))
                                        <a class="btn btn-purple-outline" href="/subscription/{{ $subscription->id }}/pay">Оплатить</a>
                                    @else
                                        <a class="btn btn-purple-outline" href="/subscribe/{{ $subscription->id }}">Подписаться</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </section>

@endsection