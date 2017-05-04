<body>
<h2>Ваши данные для входа в личный кабинет</h2>

<ul>
    <li>Логин - {{ $login }}</li>
    <li>Пароль - {{ $password }}</li>
</ul>

<p>
    <a href="{{ route('profile') }}" target="_blank">Личный кабинет</a>.
</p>
</body>