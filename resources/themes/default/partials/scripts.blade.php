<script type="text/javascript" src="{!! asset('assets/components/elastislide/js/modernizr.custom.17475.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/components/jquery/dist/jquery.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/components/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/components/jquery.countdown/dist/jquery.countdown.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/components/select2/dist/js/select2.full.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/components/lightbox2/dist/js/lightbox.min.js') !!}"></script>
<script type="text/javascript" src="{!! asset('assets/components/slick-carousel/slick/slick.min.js') !!}"></script>
<script type="text/javascript" src="{!! Theme::asset('js/app.js') !!}"></script>
<script type="text/javascript" src="{{ asset('assets/themes/admin/vendor/adminlte/plugins/input-mask/jquery.inputmask.js') }}"></script>

<script type="text/javascript">
    lightbox.option({
        'resizeDuration' : 100,
        'albumLabel' : 'Фото %1 из %2',
        'disableScrolling' : true,
        'fadeDuration' : 100,
        'wrapAround' : true
    });
    $('input[name=phone]').inputmask({
        mask: "89999999999"
    });
</script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter29938144 = new Ya.Metrika({
                    id:29938144,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
    <div>
        <img src="https://mc.yandex.ru/watch/29938144" style="position:absolute; left:-9999px;" alt="" />
    </div>
</noscript>
<!-- /Yandex.Metrika counter -->

<!-- Google Analytics -->
<script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-83936809-1', 'auto');
    ga('send', 'pageview');

</script>
<!-- Google Analytics -->

<!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '487109128147077');
    fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
               src="https://www.facebook.com/tr?id=487109128147077&ev=PageView&noscript=1"
    /></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
