<div class="col-lg-4 col-xs-4">
    <strong itemprop="name">
        {!! config('app.name') !!}
    </strong><br />
    <address @if(request()->url() == route('home')) itemprop="address" itemscope itemtype="http://schema.org/PostalAddress" @endif>
        <span itemprop="addressCountry">{!! variable('country') !!}</span>,
        <span itemprop="addressLocality">{!! variable('city') !!}</span>,
        <span itemprop="streetAddress">{!! variable('address') !!}</span>,
        <span itemprop="postalCode">{!! variable('postal_code') !!}</span>
    </address>
</div>