@extends('layouts.master')

@section('content')
    <section>
        <h2 class="text-center">
            {!! $model->name !!}
            <hr class="news">
        </h2>

        <br>

        @if(!sizeof($news))
            <p class="text-center">
                <i>Новостей нет</i>
            </p>
        @else
            @foreach($news as $new)
                <article id="article{{ $new->id }}">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4 col-xs-12">
                            <a href="{!! $new->image !!}" data-lightbox="articles">
                                <div class="photo" style="background-image: url('{!! $new->image ? create_thumbnail($new->image, 250, 322) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=322&w=250" !!}')">
                                    <div class="layout">
                                        <i class="fa fa-search"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-8 col-sm-8 col-xs-12">
                            <h3>{!! $new->name !!}</h3>

                            <p class="text-muted">{!! $new->publish_at !!}</p>
                            <p>{!! $new->content !!}</p>
                        </div>
                    </div>
                </article>

                <br>
            @endforeach

            <div class="text-center">
                {!! $news->links() !!}
            </div>
        @endif
    </section>
@endsection