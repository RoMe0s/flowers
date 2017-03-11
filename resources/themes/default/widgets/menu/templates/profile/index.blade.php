<button class="btn btn-default btn-block btn-mobile" data-toggle="collapse" data-target=".menu-collapse">
    <i class="fa fa-bars"></i> Меню
</button>

<div class="collapse navbar-collapse menu-collapse">
    <h4><b>Меню</b></h4>

    <ul class="nav nav-pills nav-stacked">
        @foreach($menu->visible_items as $item)
            @if(strpos('administrator', $item->link) !== FALSE)
                @if($user->hasAccess('admin'))
                    <li>
                        <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}" @endif title="{!! $item->title !!}">{!! $item->name !!}</a>
                    </li>
                @endif
            @else
                <li>
                    <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}" @endif title="{!! $item->title !!}">{!! $item->name !!}</a>
                </li>
            @endif
        @endforeach
    </ul>
</div>