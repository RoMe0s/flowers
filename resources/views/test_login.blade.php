<form action="{!! url('test_login') !!}" method="POST" style="width: 100%; display: block; text-align: center; line-height: 150px;">
    <h1>
        Вход в тестовую часть
    </h1>
    {!! csrf_field() !!}
    <input required="true" placeholder="Секретный пароль" type="password" name="password">
    <input type="submit" value="Вход"/>
</form>