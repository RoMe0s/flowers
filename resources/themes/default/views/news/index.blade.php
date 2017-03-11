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
                                <div class="photo" style="background-image: url('{!! $new->image !!}')">
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