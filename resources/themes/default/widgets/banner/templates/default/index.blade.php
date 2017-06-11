@if(sizeof($banner->items))
<div id="{!! $banner->layout_position !!}" class="carousel slide banner-carousel" data-ride="carousel">
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        @foreach($banner->items as $key => $item)
            <div class="item @if($key == 0) active @endif" style="background-image: url('{!! $item->image !!}');">
                @if(!empty($item->bullet) && file_exists(public_path() . $item->bullet))
                    <img src="{!! $item->bullet !!}" class="bullet" />
                @endif
                <img src="{!! $item->image !!}" alt="{{$item->name}}" class="main">
                <div class="carousel-caption">
                    <div class="inner">
                        <h2>{{$item->name}}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if(count($banner->items) > 1)
        <!-- Left and right controls -->
        <a class="left carousel-control" href="#{!! $banner->layout_position !!}" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#{!! $banner->layout_position !!}" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    @endif
</div>
@endif