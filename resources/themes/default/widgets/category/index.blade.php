@if(sizeof($categories))
    @php($counter = 0)
    @foreach($categories as $key => $category)
        @if($counter == 0) <div class="row"> @endif
            <div class="col-sm-6 col-xs-12">
                <a href="{!! url($category->slug) !!}">
                    <div class="photo" style="background-image: url('{!! $category->image !!}');">
                        <div class="title">{!! $category->name !!}</div>
                    </div>
                </a>
            </div>
            @php($counter++)
            @if($counter == 2 || $key == count($categories) - 1) </div> @endif
        @if($counter == 2) @php($counter = 0) @endif
    @endforeach
    <br />
@endif