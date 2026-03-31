<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
<form method="POST" action="{{ route('register.perform') }}" class="auth-card">
    @csrf
    <h1>Регистрация</h1>
    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    @endif
    <div class="stack auth-stack">
        <input name="name" placeholder="Имя" class="form-input" value="{{ old('name') }}" required>
        <input name="email" type="email" placeholder="Эл. почта" class="form-input" value="{{ old('email') }}" required>
        <select name="role" class="form-select" required>
            <option value="manager" @selected(old('role') === 'manager')>Менеджер</option>
            <option value="master" @selected(old('role') === 'master')>Мастер</option>
            <option value="mechanic" @selected(old('role') === 'mechanic')>Механик</option>
            <option value="painter" @selected(old('role') === 'painter')>Маляр</option>
        </select>
        <input name="password" type="password" placeholder="Пароль" class="form-input" required>
        <input name="password_confirmation" type="password" placeholder="Пароль ещё раз" class="form-input" required>
        <button type="submit" class="btn btn-primary btn-block">Создать аккаунт</button>
        <a href="{{ route('login') }}" class="link-accent">Вход</a>
    </div>
</form>
</body>
</html>
