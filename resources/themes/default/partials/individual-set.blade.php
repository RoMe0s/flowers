<div class="individual">
    <section>
        @widget__text_widget(5)

        <form action="{!! route('api.individual.store') !!}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}

            <div class="row">
                @include('errors.form')
                <div class="col-lg-4 col-sm-4 col-xs-12 col-sm-offset-2 col-lg-offset-2">

                    <p>
                        <input class="form-control input-outline" type="text" name="price" value="{{ old('price') }}" placeholder="Желаемая стоимость" min="1" required>
                    </p>
                    <p>
                        <input class="form-control input-outline" type="file" name="image" value="{{ old('image') }}" required>
                    </p>

                    @if($user)
                        <p>
                            <input class="form-control input-outline" type="text" name="phone" placeholder="Контактный телефон" value="{!! $user->phone !!}" required>
                        </p>
                        <p>
                            <input class="form-control input-outline" type="email" name="email" placeholder="Контактный email" value="{!! $user->email !!}">
                        </p>
                    @else
                        <p>
                            <input class="form-control input-outline" type="text" name="phone" value="{{ old('phone') }}" placeholder="Контактный телефон" required>
                        </p>
                        <p>
                            <input class="form-control input-outline" type="email" name="email" value="{{ old('email') }}" placeholder="Контактный email">
                        </p>
                    @endif
                </div>
                <div class="col-lg-4 col-sm-4 col-xs-12">
                    <p>
                        <textarea class="form-control input-outline" name="text" rows="5" placeholder="Примечания" value="{{ old('text') }}"></textarea>
                    </p>
                    <p class="text-right">
                        <input class="btn btn-default input-outline" type="submit" value="Заказать">
                    </p>
                </div>
            </div>
        </form>
    </section>
</div>