<header>
    <div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <p>
                            <a href="tel:84955323835">8 (495) 532-38-35</a>
                        </p>
                        <p>
                            <a href="tel:89684583838">8 (968) 458-38-38</a>
                        </p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 info">
                        <p>c 10:00 до 21:00</p>
                        <p>
                            <i class="fa fa-whatsapp"></i>
                            <i class="fa fa-send-o"></i>
                            <i class="fa fa-phone"></i>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-2 col-xs-6">
                <p class="text-center" style="margin: 0;">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="">
                    </a>
                </p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6">
                <p class="text-right">Мы в социальных сетях</p>
                <p class="text-right">
                    <a href="http://www.instagram.com/flowersmoscow24" target="_blank" title="Мы в Instagram">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="http://vk.com/flowersmoscow247" target="_blank" title="Мы Вконтакте">
                        <i class="fa fa-vk"></i>
                    </a>
                    <a href="https://www.facebook.com/flowersmoscow24.ru" target="_blank" title="Мы в Facebook">
                        <i class="fa fa-facebook-official"></i>
                    </a>
                </p>
            </div>
        </div>

        @include('errors.form')
    </div>

    @widget__menu(1)

    <div id="cart">
        <div class="cart-body">
            <h2 class="text-center text-uppercase">
                Корзина
                <hr>
            </h2>

            <div class="cart-items">
                <div class="item">
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</header>