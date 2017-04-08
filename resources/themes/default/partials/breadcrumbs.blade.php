@if(isset($breadcrumbs) && count($breadcrumbs) > 2)
    <div class="col-sm-12 breadcrumbs">
        @foreach($breadcrumbs as $key => $breadcrumb)
            @php($key++)
            <a title="{{$breadcrumb['name']}}" @if(isset($breadcrumb['url']) && $breadcrumb['url'] !== FALSE) href="{{$breadcrumb['url']}}" @endif>{{$breadcrumb['name']}}</a>
            @if($key < count($breadcrumbs)) / @endif
        @endforeach
    </div>
@endif