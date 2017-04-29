<ul class="global-price-filter">
    @foreach($filters as $filter)
        @php($url = route('pages.sort', ['slug' => $slug, 'sort' => $filter->slug]))
            <li class="global-search" @if(request()->url() != $url) data-active="false" @else data-active="true" @endif>
                <a @if(request()->url() != $url) href="{!! $url !!}" @else href="{!! route('pages.show', ['slug' => $slug]) !!}"  @endif>
                    {!! $filter->title !!}
                    @if($url == request()->url())
                        <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                    @endif
                </a>
            </li>
    @endforeach
</ul>