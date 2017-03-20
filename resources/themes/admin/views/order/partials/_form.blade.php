@include('order.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
{{--            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#client" data-toggle="tab">@lang('labels.tab_client')</a>
                </li>

                @if(!$model->isSubscriptionOrder())
                    <li>
                        <a aria-expanded="false" href="#address" data-toggle="tab">@lang('labels.tab_address')</a>
                    </li>
                @endif

                <li>
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#items" data-toggle="tab">@lang('labels.tab_items')</a>
                </li>
            </ul>--}}

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
                    <div class="tab-pane" id="general">
                            @include('order.tabs.general')
                    </div>
                </div>

{{--            @if(!$model->isSubscriptionOrder())
                    <div class="tab-pane" id="address">
                        @include('order.tabs.address')
                    </div>
                @else
                    {!! Form::hidden('address_need', '1') !!}
                @endif

                <div class="tab-pane" id="general">
                    @include('order.tabs.general')
                </div>
                @if(isset($model->id))
                    <div class="tab-pane" id="items">
                        @include('order.tabs.items') 
                    </div>
                    @else
                    <div class="tab-pane basket-items-list" id="items">
                        @include('order.tabs.basket_items')
                    </div>
                @endif --}}
            </div>
        </div>

    </div>
</div>

@include('order.partials._buttons')