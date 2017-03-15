<body>
<h2>Заказ #{{ $order->id }} оплачен</h2>

<p>
    <a href="{{ route('admin.order.edit', ['id' => $order->id]) }}">Просмотреть информацию о заказе</a>
</p>
</body>