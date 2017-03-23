@if(sizeof($categories))
    @php($counter = 0)
    @foreach($categories as $key => $category)
        @if($counter == 0) <div class="row"> @endif
            <div class="@if($key < 2) col-sm-6 @else col-sm-4 @endif col-xs-6">
                <a href="{!! url($category->slug) !!}">
                    <div class="photo" style="background-image: url('{!! $category->image !!}');">
                        <div class="title">{!! $category->name !!}</div>
                    </div>
                </a>
            </div>
            @php($counter++)
            @if( ($key == 1 && $counter == 2) || ($key >= 2 && $counter == 3) || $key == count($categories) - 1) </div> @endif
            @if( ($key == 1 && $counter == 2) || $key >= 2 && $counter == 3 ) @php($counter = 0) @endif
    @endforeach
    <br />
@endif