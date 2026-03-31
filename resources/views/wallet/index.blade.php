@extends('layouts.app')

@section('content')
    <h1 class="page-title">Касса (laravel-wallet)</h1>
    <p class="wallet-balance-intro">Баланс: <span class="wallet-balance-val">{{ $treasury->balanceFloat }}</span></p>

    <div class="grid-2">
        <form method="POST" action="{{ route('wallet.deposit') }}" class="form-card form-card--stack">
            @csrf
            <div class="wallet-label-in">Приход</div>
            <input type="number" step="0.01" min="0.01" name="amount" placeholder="Сумма" class="form-input" required>
            <input type="text" name="comment" placeholder="Комментарий" class="form-input">
            <button type="submit" class="btn btn-success">Внести</button>
        </form>
        <form method="POST" action="{{ route('wallet.withdraw') }}" class="form-card form-card--stack">
            @csrf
            <div class="wallet-label-out">Расход</div>
            <input type="number" step="0.01" min="0.01" name="amount" placeholder="Сумма" class="form-input" required>
            <input type="text" name="comment" placeholder="Комментарий" class="form-input">
            <button type="submit" class="btn btn-rose">Снять</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
            <tr>
                <th>№</th>
                <th>Тип</th>
                <th>Сумма</th>
                <th>Комментарий</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody>
            @foreach($transactions as $tx)
                <tr>
                    <td>{{ $tx->id }}</td>
                    <td>{{ $tx->type }}</td>
                    <td>{{ $tx->amountFloat }}</td>
                    <td class="truncate-cell" title="{{ is_array($tx->meta) ? ($tx->meta['comment'] ?? json_encode($tx->meta, JSON_UNESCAPED_UNICODE)) : '' }}">
                        {{ is_array($tx->meta) ? ($tx->meta['comment'] ?? '—') : '—' }}
                    </td>
                    <td class="nowrap">{{ $tx->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $transactions->links() }}
@endsection
