@if(isset($breadcrumbs) && count($breadcrumbs) > 2)
    <ul class="col-sm-12 breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">
        @foreach($breadcrumbs as $key => $breadcrumb)
            @php($key++)
            <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                <a itemprop="item" title="{{$breadcrumb['name']}}" @if(isset($breadcrumb['url']) && $breadcrumb['url'] !== FALSE) href="{{$breadcrumb['url']}}" @else content="{!! url()->current() !!}"  @endif>
                    <span itemprop="name">{{$breadcrumb['name']}}</span>
                    <meta itemprop="position" content="{!! $key !!}" />
                </a>
            </li>
        @endforeach
    </ul>
@endif