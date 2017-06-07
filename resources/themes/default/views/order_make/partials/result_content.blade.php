<div class="text-center order-result-info">
    @php($name = isset($data['recipient_name']) && !empty($data['recipient_name']) ? $data['recipient_name'] : $user->name)
    @if(!empty($name))
    <p>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
        {{ $name }}
    </p>
    @endif
    <p>
        <i class="fa fa-mobile" aria-hidden="true"></i>
        {{ isset($data['recipient_phone']) && !empty($data['recipient_phone']) ? $data['recipient_phone'] : $user->phone }}
    </p>
    @if(isset($data['anonymously']) && $data['anonymously'])
    <p>
        Анонимно
        <i class="fa fa-check text-success" aria-hidden="true"></i>
    </p>
    @endif
    @if($data['address'])
        <p>
            <i class="fa fa-map-marker" aria-hidden="true"></i>
            {{$data['address']}}
        </p>
    @endif
    @if((isset($data['date']) && !empty($data['date'])) || (isset($data['time']) && !empty($data['time'])))
        <p>
            <i class="fa fa-calendar" aria-hidden="true"></i>
            {{isset($data['date']) ? $data['date'] : ""}}
            {{isset($data['time']) ? $data['time'] : ""}}
        </p>
    @endif
    @if(isset($data['neighbourhood']) && $data['neighbourhood'])
        <p>
            Если получателя не будет на месте, оставить заказ родственникам/друзьям/коллегам
            <i class="fa fa-check text-success" aria-hidden="true"></i>
        </p>
    @endif
</div>
<ul class="list-group">
    @foreach($items as $item)
    <li class="list-group-item clearfix">
        <img src="{!! thumb($item->options['image'], 75) !!}" class="pull-left"
             alt="{{$item->name}}"/>
        <a target="_blank" href="{!! $item->options['url'] !!}"
           title="{{$item->name}}">
            {{$item->name}}
            {{$item->qty}}
            шт. на сумму
            {{$item->subtotal}}
            руб.
        </a>
    </li>
    @endforeach
    @if(get_delivery_price() > 0)
    <li class="list-group-item clearfix">
            <span class="text-center col-xs-12">
                Стоимость доставки - {!! get_delivery_price() !!} руб.
            </span>
    </li>
    @endif
    @if(isset($data['night']) && $data['night'])
        <li class="list-group-item clearfix">
            <span class="text-center col-xs-12">
                Ночная доставка - {!! variable('night-delivery', 800) !!} руб.
            </span>
        </li>
    @endif
    @if(isset($data['accuracy']) && $data['accuracy'])
        <li class="list-group-item clearfix">
            <span class="text-center col-xs-12">
                Точность до 15 минут - {!! variable('accuracy-delivery', 300) !!} руб.
            </span>
        </li>
    @endif
    @if(isset($distance) && $distance > 0)
        @php($price = $distance <= 5 ? variable('mkad-delivery-5', 250) . ' руб.' : ($distance <= 10 ? variable('mkad-delivery-10', 500) . ' руб.' : 'по согласованию'))
        <li class="list-group-item clearfix">
            <span class="text-center col-xs-12">
                Доставка за МКАД - {!! $price !!}
            </span>
        </li>
    @endif
</ul>
