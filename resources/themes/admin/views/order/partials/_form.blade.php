@include('order.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <div class="tab-content">
                <h4 class="text-center">Элементы</h4>
                <div class="tab-pane fade in active">
                    @if(isset($model->id))
                    <div id="items">
                        @include('order.tabs.items')
                    </div>
                    @else
                        <div class="basket-items-list" id="items">
                            @include('order.tabs.basket_items')
                        </div>
                    @endif
                    <h4 class="text-center">Клиент</h4>
                    <div class="tab-pane" id="client">
                        @include('order.tabs.client')
                    </div>
                        @if(!$model->isSubscriptionOrder())
                            <h4 class="text-center">Адрес</h4>
                            <div id="address">
                                @include('order.tabs.address')
                            </div>
                        @else
                            {!! Form::hidden('address_need', '1') !!}
                        @endif
                    <h4 class="text-center">Основные</h4>
                    <div id="general">
                            @include('order.tabs.general')
                    </div>
                    @if(in_array($model->status, [4,5]))
                     <h4 class="text-center">Результат</h4>
                    <div id="result">
                        @include('order.tabs.result')
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

@include('order.partials._buttons')