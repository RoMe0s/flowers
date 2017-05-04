<div class="after-register-popup">
    <div class="close-btn text-right">
        <a href="#">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>
    </div>
    <div class="row main">
        <div class="col-md-6 col-md-offset-3 col-xs-12">
            <i class="title">
                <img src="{!! Theme::asset('images/logo.png') !!}" alt="{!! config('app.name') !!}" />
                <br />
                <small>
                    Доставка цветов по Москве
                </small>
            </i>
            <p class="title text-success">
                Регистрация прошла успешно!<br />
            </p>
            <p class="text-center">
                <span class="text-primary">
                    Пароль был отправлен в SMS сообщении на указанный номер
                </span><br />
                Укажите дополнительную информацию(необязательно) для получения оповещения на Email и имя, которое будет использоваться для оформления заказов или закройте данное окно
            </p><br />
            {!! Form::open(['method' => 'POST', 'route' => 'profile.post']) !!}
            <div class="form-group">
                {!! Form::input('email', 'email', $user->email, array('class' => 'form-control input-sm', 'placeholder' => 'Email')) !!}
            </div>
            <div class="form-group">
                {!! Form::text('name', $user->name, array('class' => 'form-control input-sm', 'placeholder' => 'Полное имя')) !!}
            </div>
            <div class="text-center">
                {!! Form::submit('Отправить', array('class' => 'btn btn-purple')) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
    <div class="footer text-center">
        &copy; 2008-{{ date("Y") }} ООО "Вдохновение"
    </div>
</div>