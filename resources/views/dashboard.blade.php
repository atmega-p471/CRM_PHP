@extends('layouts.app')

@section('content')
    <h1 class="page-title">Панель</h1>
    <div class="stats-grid">
        <div class="card">
            <div class="stat-label">Баланс кассы</div>
            <div class="stat-value stat-value--emerald">{{ $treasuryBalance }}</div>
        </div>
        <div class="card">
            <div class="stat-label">Сервис: в работе</div>
            <div class="stat-value">{{ $activeServiceOrders }}</div>
        </div>
        <div class="card">
            <div class="stat-label">Кузов: в работе</div>
            <div class="stat-value">{{ $activeBodyOrders }}</div>
        </div>
        <div class="card">
            <div class="stat-label">Авто на территории</div>
            <div class="stat-value">{{ $vehiclesOnSite }}</div>
            <div class="muted-small">статусы «в сервисе» / «в кузовном»</div>
        </div>
    </div>
    <div class="card">
        <h2 class="subtitle">Последние продажи</h2>
        <div class="table-wrap table-wrap--plain">
            <table class="data-table">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Автомобиль</th>
                    <th>Цена</th>
                    <th>Статус</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                @forelse($latestSales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->vehicle?->short_label ?? $sale->vehicle_id }}</td>
                        <td>{{ $sale->sale_price }}</td>
                        <td>{{ $sale->status?->title ?? '—' }}</td>
                        <td>{{ $sale->sold_at?->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-cell">Пока нет продаж</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
