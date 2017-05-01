<footer>
    <section>

        @widget__text_widget(0)

        <div class="row">
            <div class="col-lg-4 col-xs-4">
                &copy; 2008-{{ date("Y") }} ООО "Вдохновение"
            </div>
            <div class="col-lg-4 col-xs-4">
                <a href="http://inspirationstudio.ru">Inspirationstudio.ru</a>
            </div>
            @include('partials.seo.footer_info')
        </div>
    </section>
</footer>