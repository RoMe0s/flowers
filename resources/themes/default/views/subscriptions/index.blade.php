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
                            <div class="photo photo-small" style="background-image: url('{{ $subscription->image ? create_thumbnail($subscription->image, 270, 150) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=223&w=125" }}')"></div>
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