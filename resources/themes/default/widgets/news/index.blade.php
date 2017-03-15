<br>
    <section>
        <h2 class="text-center">
            НОВОСТИ
            <hr class="news">
        </h2>

        <br>

        @if(!sizeof($news))
            <p class="text-center">
                <i>Новостей нет</i>
            </p>
        @else
            <div class="row">
                @for($i = 0; $i < count($news); $i++)
                    @if($i % 2 == 0 && $i != 0) </div><br><div class="row"> @endif

                    <div class="col-lg-6 col-xs-12">
                        <article>
                            <div class="row">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <a href="{!! $news[$i]->image !!}" data-lightbox="news">
                                        <div class="photo" style="background-image: url('{!! $news[$i]->image !!}')">
                                            <div class="layout">
                                                <i class="fa fa-search"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-7 col-sm-6 col-xs-12">
                                    <p>
                                        <a href="{!! route('news') !!}#article{!! $news[$i]->id !!}" title="{!! $news[$i]->name !!}">
                                            <b>{!! $news[$i]->name !!}</b>
                                        </a>
                                    </p>
                                    <p>
                                        <span class="text-muted">{!! $news[$i]->publish_at !!}</span>
                                    </p>
                                    <p>{!! $news[$i]->getShortContent().'...' !!}</p>
                                </div>
                            </div>
                        </article>
                    </div>
                @endfor
            </div>

            <br>

            <p class="text-center">
                <a class="text-muted" href="{!! route('news') !!}">БОЛЬШЕ НОВОСТЕЙ</a>
            </p>
        @endif
    </section>
<br>