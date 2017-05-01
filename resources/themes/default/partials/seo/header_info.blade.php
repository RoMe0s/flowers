    <div>
        <span class="invisible" itemprop="url" content="{!! route('home') !!}"></span>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <p>
                            @php( $phone = preg_replace('/\D/', '', variable('phone')) )
                            <a itemprop="telephone" content="{!! $phone !!}" href="tel:{!! $phone !!}">
                                {!! variable('phone') !!}
                            </a>
                        </p>
                        <p>
                            @php( $phone = preg_replace('/\D/', '', variable('services_phone')) )
                            <a itemprop="telephone" content="{!! $phone !!}" href="tel:{!! $phone !!}">{!! variable('services_phone') !!}</a>
                        </p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 info">
                        <p itemprop="openingHours" content="Пн.-Вс. 10:00-21:00">{!! variable('work_times') !!}</p>
                        <p>
                            <i class="fa fa-whatsapp"></i>
                            <i class="fa fa-send-o"></i>
                            <i class="fa fa-phone"></i>
                        </p>
                    </div>
                    <div class="col-xs-12 invisible">
                        <a href="mailto:{!! variable('showing_email') !!}" itemprop="email" content="{!! variable('showing_email') !!}">
                            {!! variable('showing_email') !!}
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-2 col-xs-6">
                <p class="text-center" style="margin: 0;">
                    <a @if(request()->path() != '/') href="/" @endif>
                        <img itemprop="image" src="{{ Theme::asset('images/logo.png') }}" alt="{!! config('app.name') !!}">
                    </a>
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6">
                <p class="text-right">Мы в социальных сетях</p>
                <p class="text-right">
                    <a href="http://www.instagram.com/flowersmoscow24" target="_blank" title="Мы в Instagram" rel="nofollow, noindex">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="http://vk.com/flowersmoscow247" target="_blank" title="Мы Вконтакте" rel="nofollow, noindex">
                        <i class="fa fa-vk"></i>
                    </a>
                    <a href="https://www.facebook.com/flowersmoscow24.ru" target="_blank" title="Мы в Facebook" rel="nofollow, noindex">
                        <i class="fa fa-facebook-official"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>