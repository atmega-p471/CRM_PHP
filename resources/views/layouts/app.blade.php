<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRM Авто</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="app-layout">
    <aside class="app-sidebar">
        <div class="app-sidebar__title">CRM Авто</div>
        <nav class="app-nav">
            <a href="{{ route('dashboard') }}">Панель</a>
            <a href="{{ route('clients.index') }}">Клиенты</a>
            <a href="{{ route('vehicles.index') }}">Автомобили</a>
            <a href="{{ route('supplies.index') }}">Автосалон: поставки</a>
            <a href="{{ route('sales.index') }}">Автосалон: продажи</a>
            <a href="{{ route('service_orders.index') }}">Сервис: заказ-наряды</a>
            <a href="{{ route('spare_parts.index') }}">Сервис: запчасти</a>
            <a href="{{ route('service_items.index') }}">Сервис: услуги</a>
            <a href="{{ route('body_repair_orders.index') }}">Кузов: заказы</a>
            <a href="{{ route('paint_materials.index') }}">Кузов: материалы</a>
            <a href="{{ route('wallet.index') }}">Касса</a>
            <a href="{{ route('users.index') }}">Сотрудники</a>
        </nav>
        <div class="app-sidebar__logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Выйти</button>
            </form>
        </div>
    </aside>
    <main class="app-main">
        @if(session('ok'))
            <div class="alert alert--success">{{ session('ok') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert--error">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
