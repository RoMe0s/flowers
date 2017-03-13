<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active">
            <a aria-expanded="false" href="#info" data-toggle="tab">@lang('labels.tab_info')</a>
        </li>

        <li class="@if ($errors->has('groups')) tab-with-errors @endif">
            <a aria-expanded="false" href="#groups" data-toggle="tab">@lang('labels.tab_groups')</a>
        </li>

        <li class="@if ($errors->has('orders')) tab-with-errors @endif">
            <a aria-expanded="false" href="#orders" data-toggle="tab">@lang('labels.tab_orders')</a>
        </li>

        <li class="@if ($errors->has('subscriptions')) tab-with-errors @endif">
            <a aria-expanded="false" href="#subscriptions" data-toggle="tab">@lang('labels.tab_subscriptions')</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="info">
            @include('user.tabs.info')
        </div>

        <div class="tab-pane" id="groups">
            @include('user.tabs.groups')
        </div>

        <div class="tab-pane" id="orders">
            @include('user.tabs.orders')
        </div>

        <div class="tab-pane" id="subscriptions">
            @include('user.tabs.subscriptions')
        </div>
    </div>
</div>
