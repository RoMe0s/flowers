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

                    <div class="col-lg-6 col-xs-6">
                        <article>
                            <div class="row items-list">
                                <div class="col-md-5 col-sm-6 col-xs-12 item">
                                    <a href="{!! $news[$i]->image !!}" data-lightbox="news">
                                        <div class="photo">
                                            <img src="{!! $news[$i]->image ? create_thumbnail($news[$i]->image, 250, 300) : "https://placeholdit.imgix.net/~text?txtsize=14&bg=efefef&txtclr=aaaaaa%26text%3Dno%2Bimage&txt=%D0%BD%D0%B5%D1%82+%D0%BA%D0%B0%D1%80%D1%82%D0%B8%D0%BD%D0%BA%D0%B8&h=190&w=250" !!}" alt="{!! $news[$i]->name !!}" />
                                            <div class="layout">
                                                <p>
                                                    <i class="fa fa-search"></i>
                                                    <span>
                                                        смотреть
                                                    </span>
                                                </p>
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
                                    <p>{!! $news[$i]->getShortContent(true).'...' !!}</p>
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
