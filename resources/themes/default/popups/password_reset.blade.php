
<div class="after-register-popup password-reset-popup">
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
                Введите код из смс!<br />
            </p>
            <br />
            <br />
            <div class="col-xs-offset-3">
                {!! Form::open(['method' => 'POST', 'url' => url('password-reset-phone')]) !!}
                {!! Form::hidden('login', $login) !!}
                <div class="form-group col-xs-2">
                    {!! Form::text('code[1]', null, array('class' => 'form-control input-sm text-center', 'maxlength' => 1, 'required' => true)) !!}
                </div>
                <div class="form-group col-xs-2">
                    {!! Form::text('code[2]', null, array('class' => 'form-control input-sm text-center', 'maxlength' => 1, 'required' => true)) !!}
                </div>
                <div class="form-group col-xs-2">
                    {!! Form::text('code[3]', null, array('class' => 'form-control input-sm text-center', 'maxlength' => 1, 'required' => true)) !!}
                </div>
                <div class="form-group col-xs-2">
                    {!! Form::text('code[4]', null, array('class' => 'form-control input-sm text-center', 'maxlength' => 1, 'required' => true)) !!}
                </div>
                {!! Form::close() !!}
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="footer text-center">
        &copy; 2008-{{ date("Y") }} ООО "Вдохновение"
    </div>
</div>