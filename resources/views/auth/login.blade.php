<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
<form method="POST" action="{{ route('login.perform') }}" class="auth-card stack">
    @csrf
    <h1>Вход в CRM Авто</h1>
    @if ($errors->any())
        <div class="error-list-wrap">
            <div class="alert alert--error">{{ $errors->first() }}</div>
        </div>
    @endif
    <div class="stack">
        <input name="email" type="email" placeholder="Эл. почта" class="form-input" value="{{ old('email') }}" required autofocus>
        <input name="password" type="password" placeholder="Пароль" class="form-input" required>
        <label class="row-check">
            <input type="checkbox" name="remember" value="1">
            Запомнить меня
        </label>
        <button type="submit" class="btn btn-primary btn-block">Войти</button>
        <a href="{{ route('register') }}" class="link-accent">Регистрация</a>
    </div>
</form>
</body>
</html>
