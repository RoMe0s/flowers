<body>
<h2>Ваши данные для входа в личный кабинет</h2>

<ul>
    <li>Email - {{ $email }}</li>
    <li>Пароль - {{ $password }}</li>
</ul>

<p>
    <a href="{{ route('profile') }}" target="_blank">Личный кабинет</a>.
</p>
</body>