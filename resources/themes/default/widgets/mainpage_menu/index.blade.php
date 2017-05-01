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
                @if(sizeof($filters))
                    <li class="title">
                            Цены
                    </li>
                @endif
                @foreach($filters as $filter)
                    <li>
                        <a href="{!! route('flowers_sort', ['sort' => $filter->slug]) !!}" title="{!! $filter->title !!}">
                          {!! $filter->title !!}
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
