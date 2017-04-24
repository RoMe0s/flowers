{{--<div id="mobile-categories">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <ul class="categories-list">
        @foreach($categories as $key => $category)
            <li>
                <a href="{!! $category->getUrl() !!}" title="{!! $category->name !!}">
                    {{$category->name}}
                </a>
            </li>
        @endforeach
    </ul>
</div>--}}

<div class="mainpage_menu row">
    <div class="sticky-wrapper col-sm-12 col-md-3">
        <div class="mobile-button">
            <button class="btn btn-purple-outline form-control" onclick="collapseCategoriesList()">
                <i class="fa fa-bars"></i>
                Выбрать
            </button>
        </div>
        <div class="sidebar">
            <ul class="categories-list">
                @foreach($list as $key => $item)
                    <li>
                        <a href="{!! $item->data->getUrl() !!}" title="{!! $item->data->name !!}">
                            {{$item->data->name}}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="content col-sm-12 col-md-9">
        @include('widgets.mainpage_menu.partials.category')
    </div>
    <div class="clearfix"></div>
</div>