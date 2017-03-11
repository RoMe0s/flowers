<div class="image">
    <section>

    @widget__text_widget(3)

        <form action="{!! route('api.help') !!}" method="post">
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-lg-3 col-sm-3 col-xs-12 col-lg-offset-2 col-sm-offset-2 col-xs-offset-0">
                    <p>
                        <input class="form-control input-outline" type="text" name="name" value="{{ old('name') }}" placeholder="Имя" required>
                    </p>
                </div>
                <div class="col-lg-3 col-sm-3 col-xs-12">
                    <p>
                        <input class="form-control input-outline" type="text" name="phone" value="{{ old('phone') }}" placeholder="Телефон" maxlength="11" required>
                    </p>
                </div>
                <div class="col-lg-2  col-sm-2 col-xs-12">
                    <p>
                        <input class="btn btn-outline" type="submit" value="ЗАКАЗАТЬ">
                    </p>
                </div>
            </div>
        </form>
    </section>
</div>