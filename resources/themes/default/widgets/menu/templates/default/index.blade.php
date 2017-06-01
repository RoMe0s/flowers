<nav class="nav navbar-pills header-menu">
    <a href="#" data-show-basket class="navbar-toggle float-left mobile-cart">
        <i class="fa fa-shopping-bag"></i>
        <span class="cart-count">
            {!! Cart::count() !!}
        </span>
    </a>
    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#nav-collapse">
        Меню <i class="fa fa-bars"></i>
    </button>

    <div class="clearfix"></div>

    <div class="collapse navbar-collapse" id="nav-collapse">
        <div class="container">
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
                        <a @if(sizeof($child_items)) class="dropdown-toggle" data-toggle="dropdown" @endif @if(trim(request()->path(), '/') != trim($parent_item->link, '/')) href="{!! $parent_item->link !!}" @endif  title="{!! $parent_item->title !!}">
                             {!! $parent_item->name !!} @if(sizeof($child_items)) <i class="caret-down"></i> @endif
                        </a>

                        @if(sizeof($child_items))
                            <ul class="dropdown-menu">
                                @foreach($child_items as $item)
                                    <li>
                                        <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}" @endif title="{!! $item->name !!}">{!! $item->name !!}</a>
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
                            <a @if(sizeof($child_items)) class="dropdown-toggle" data-toggle="dropdown" @endif @if(trim(request()->path(), '/') != trim($parent_item->link, '/')) href="{!! $parent_item->link !!}" @endif title="{!! $parent_item->title !!}">
                                {!! $parent_item->name !!} @if(sizeof($child_items)) <i class="caret-down"></i> @endif
                            </a>

                            @if(sizeof($child_items))
                                <ul class="dropdown-menu">
                                    @foreach($child_items as $item)
                                        <li>
                                            <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}" @endif title="{!! $item->name !!}">{!! $item->name !!}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @endif
            </ul>

            @php($left_items = $menu->visible_items->where('class', NULL)->where('parent_id', NULL))
            @if(sizeof($left_items))
            <ul>
                @foreach($left_items->where('register_only', -1) as $parent_item)

                @php($child_items = $menu->visible_items->where('parent_id', $parent_item->id)->where('register_only', -1))
                  <li @if(sizeof($child_items)) class="dropdown" @endif>
                      <a @if(sizeof($child_items)) class="dropdown-toggle" data-toggle="dropdown" @endif @if(trim(request()->path(), '/') != trim($parent_item->link, '/')) href="{!! $parent_item->link !!}" @endif title="{!! $parent_item->title !!}">
                          {!! $parent_item->name !!} @if(sizeof($child_items)) <i class="caret-down"></i> @endif
                      </a>

                      @if(sizeof($child_items))
                          <ul class="dropdown-menu">
                              @foreach($child_items as $item)
                                  <li>
                                      <a @if(trim(request()->path(), '/') != trim($item->link, '/')) href="{!! $item->link !!}" @endif title="{!! $item->name !!}">{!! $item->name !!}</a>
                                  </li>
                              @endforeach
                          </ul>
                      @endif
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</nav>
