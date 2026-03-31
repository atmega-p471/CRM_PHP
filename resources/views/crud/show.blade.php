@extends('layouts.app')

@section('content')
    <h1 class="page-title">{{ $cfg['label'] }} #{{ $item->id }}</h1>
    <div class="card max-w-3xl">
        <div class="stack">
        @foreach($cfg['fields'] as $field => $fieldLabel)
            @continue($field === 'password')
            <div>
                <span class="field-label">{{ $fieldLabel }}:</span>
                @if($field === 'status_id')
                    {{ $item->status?->title ?? '—' }}
                @elseif($field === 'client_id')
                    {{ $item->client?->name ?? '—' }}
                @elseif($field === 'vehicle_id')
                    {{ $item->vehicle?->short_label ?? '—' }}
                @else
                    {{ $item->{$field} }}
                @endif
            </div>
        @endforeach
        </div>
    </div>
    <div class="page-footer">
        <a href="{{ route($entityKey.'.edit', $item->id) }}" class="link-accent">Редактировать</a>
        <span class="divider-text">|</span>
        <a href="{{ route($entityKey.'.index') }}" class="link-accent">К списку</a>
    </div>
@endsection
