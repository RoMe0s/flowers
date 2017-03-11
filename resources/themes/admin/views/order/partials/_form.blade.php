@include('order.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a aria-expanded="false" href="#client" data-toggle="tab">@lang('labels.tab_client')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#address" data-toggle="tab">@lang('labels.tab_address')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.tab_general')</a>
                </li>

                <li>
                    <a aria-expanded="false" href="#items" data-toggle="tab">@lang('labels.tab_items')</a>
                </li>
            </ul>

            @php($discount = isset($model->id) ? $model->discount : $user->getDiscount())

            <div class="tab-content">
                <div class="tab-pane fade in active" id="client">
                    @include('order.tabs.client')
                </div>

                <div class="tab-pane" id="address">
                    @include('order.tabs.address')
                </div>

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
                    @endif   
            </div>
        </div>

    </div>
</div>

@include('order.partials._buttons')