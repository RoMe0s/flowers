@if(request()->url() == route('home'))
<div class="col-lg-4 col-xs-4">
    <strong itemprop="name">
        {!! config('app.name') !!}
    </strong><br />
    <address  itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <span itemprop="addressCountry">{!! variable('country') !!}</span>,
        <span itemprop="addressLocality">{!! variable('city') !!}</span>,
        <span itemprop="streetAddress">{!! variable('address') !!}</span>,
        <span itemprop="postalCode">{!! variable('postal_code') !!}</span>
    </address>
</div>
@else
    <div class="col-lg-4 col-xs-4">
        <strong>
            {!! config('app.name') !!}
        </strong><br />
        <address>
            <span>{!! variable('country') !!}</span>,
            <span>{!! variable('city') !!}</span>,
            <span>{!! variable('address') !!}</span>,
            <span>{!! variable('postal_code') !!}</span>
        </address>
    </div>
@endif