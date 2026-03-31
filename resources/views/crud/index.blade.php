@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1 class="page-title page-title--row">{{ $cfg['label'] }}</h1>
        <a href="{{ route($entityKey.'.create') }}" class="btn btn-primary">Добавить</a>
    </div>
    <div class="table-wrap">
        <table class="data-table data-table--wide">
            <thead>
            <tr>
                <th>№</th>
                @foreach($cfg['index'] as $col => $colLabel)
                    <th>{{ $colLabel }}</th>
                @endforeach
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    @foreach($cfg['index'] as $col => $colLabel)
                        <td>
                            @if($col === 'status_id')
                                {{ $item->status?->title ?? '—' }}
                            @elseif($col === 'client_id')
                                {{ $item->client?->name ?? '—' }}
                            @elseif($col === 'vehicle_id')
                                {{ $item->vehicle?->short_label ?? '—' }}
                            @else
                                {{ $item->{$col} }}
                            @endif
                        </td>
                    @endforeach
                    <td class="cell-actions">
                        <a class="link-accent" href="{{ route($entityKey.'.show', $item->id) }}">Открыть</a>
                        <a class="link-accent" href="{{ route($entityKey.'.edit', $item->id) }}">Изменить</a>
                        <form class="inline" method="POST" action="{{ route($entityKey.'.destroy', $item->id) }}" onsubmit="return confirm('Удалить запись?');" style="display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-danger-plain">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $items->links() }}
@endsection
