@if(sizeof($categories))
    {{--@php($counter = 0)--}}
    <div class="row">
    @foreach($categories as $key => $category)
        {{--@if($counter == 0) <div class="row"> @endif--}}
            <div class="@if($key < 2) col-sm-6 @else col-sm-4 @endif col-xs-6">
                <a href="{!! url($category->slug) !!}">
                    <div class="photo" style="background-image: url('{!! create_thumbnail($category->image, $key < 2 ? 500 :  350,  250) !!}')">
                        <img src="{!! $category->image ? create_thumbnail($category->image, $key < 2 ? 500 :  350,  250) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=350&w=250" !!}" alt="{{ $category->name }}" />
                        <div class="title">
                            <span>
                                {!! $category->name !!}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
{{--            @php($counter++)
            @if( ($key == 1 && $counter == 2) || ($key >= 2 && $counter == 3) || $key == count($categories) - 1) </div> @endif
            @if( ($key == 1 && $counter == 2) || $key >= 2 && $counter == 3 ) @php($counter = 0) @endif--}}
    @endforeach
    </div>
    <br />
@endif