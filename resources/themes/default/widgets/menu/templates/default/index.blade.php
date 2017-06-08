<div class="clearfix nav-mobile-buttons">
    <button class="navbar-toggle ssm-toggle-nav float-left" type="button">
        <i class="fa fa-bars"></i>
    </button>
    <a href="#" data-show-basket class="navbar-toggle float-right mobile-cart">
        <i class="fa fa-shopping-bag"></i>
        <span class="cart-count">
                {!! Cart::count() !!}
            </span>
    </a>
</div>
<nav class="nav navbar-pills text-center">
    <div class="container" id="nav-collapse">
        @php($left_items = $menu->visible_items->where('class', NULL)->where('parent_id', NULL))
        @if(sizeof($left_items))
            <ul class="float-left">
                <li class="mobile-menu-title">
                    <a {!! check_current_url(route('home')) !!} title="{!! config('app.name') !!}">
                        {!! config('app.name') !!}
                    </a>
                    <i class="fa fa-times pull-right ssm-toggle-nav"></i>
                </li>
                @foreach($left_items->where('register_only', -1) as $parent_item)

                    @php($child_items = $menu->visible_items->where('parent_id', $parent_item->id)->where('register_only', -1))
                    <li @if(sizeof($child_items)) class="dropdown" @endif>
                        <a @if(sizeof($child_items)) class="dropdown-toggle" data-toggle="dropdown"
                           @endif @if(trim(request()->path(), '/') != trim($parent_item->link, '/')) href="{!! $parent_item->link !!}"
                           @endif title="{!! $parent_item->title !!}">
                            {!! $parent_item->name !!} @if(sizeof($child_items)) <i class="caret-down"></i> @endif
                        </a>

                        @if(sizeof($child_items))
                            <ul class="dropdown-menu">
                                @foreach($child_items as $item)
                                    <li>
                                        <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}"
                                           @endif title="{!! $item->name !!}">{!! $item->name !!}</a>
                                    </li>
                                @endforeach
                            </ul>
                    @endif
                @endforeach
            </ul>
        @endif
        <ul class="float-right">
            <li class="mobile-hidden">
                <a href="#" data-show-basket>
                    <i class="fa fa-shopping-bag">
                    </i> <span class="cart-count">
                        {!! Cart::count() !!}
                        </span>
                </a>
            </li>
            @php($right_items = $menu->visible_items->where('class', 'float-right')->where('parent_id', NULL))
            @if(Sentry::check())
                @foreach($right_items->where('register_only', 1) as $parent_item)
                    @php($child_items = $menu->visible_items->where('parent_id', $parent_item->id)->where('register_only', 1))
                    <li @if(sizeof($child_items)) class="dropdown" @endif>
                        <a @if(sizeof($child_items)) class="dropdown-toggle" data-toggle="dropdown"
                           @endif @if(trim(request()->path(), '/') != trim($parent_item->link, '/')) href="{!! $parent_item->link !!}"
                           @endif  title="{!! $parent_item->title !!}">
                            {!! $parent_item->name !!} @if(sizeof($child_items)) <i class="caret-down"></i> @endif
                        </a>

                        @if(sizeof($child_items))
                            <ul class="dropdown-menu">
                                @foreach($child_items as $item)
                                    <li>
                                        <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}"
                                           @endif title="{!! $item->name !!}">{!! $item->name !!}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @else
                @foreach($right_items->where('register_only', 0) as $parent_item)
                    @php($child_items = $menu->visible_items->where('parent_id', $parent_item->id)->where('register_only', 0))
                    <li @if(sizeof($child_items)) class="dropdown" @endif>
                        <a @if(sizeof($child_items)) class="dropdown-toggle" data-toggle="dropdown"
                           @endif @if(trim(request()->path(), '/') != trim($parent_item->link, '/')) href="{!! $parent_item->link !!}"
                           @endif title="{!! $parent_item->title !!}">
                            {!! $parent_item->name !!} @if(sizeof($child_items)) <i class="caret-down"></i> @endif
                        </a>

                        @if(sizeof($child_items))
                            <ul class="dropdown-menu">
                                @foreach($child_items as $item)
                                    <li>
                                        <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}"
                                           @endif title="{!! $item->name !!}">{!! $item->name !!}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</nav>
