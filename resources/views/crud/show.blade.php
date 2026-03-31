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
                @elseif($field === 'service_item_ids')
                    @continue
                @elseif(in_array($field, ['opened_at', 'started_at', 'received_at', 'sold_at'], true))
                    {{ $item->{$field} ? $item->{$field}->timezone(config('app.timezone'))->format('d.m.Y H:i') : '—' }}
                @else
                    {{ $item->{$field} }}
                @endif
            </div>
        @endforeach
        </div>
    </div>
    @if($entityKey === 'service_orders')
        @php $item->loadMissing('serviceItems'); @endphp
        <div class="card max-w-3xl mt-4">
            <h2 class="page-title" style="font-size:1.1rem">Услуги в наряде</h2>
            <ul class="stack" style="margin:0;padding-left:1.25rem">
                @forelse($item->serviceItems as $si)
                    <li>{{ $si->name }} — {{ $si->price }} ₽@if(($si->pivot->quantity ?? 1) > 1) ×{{ $si->pivot->quantity }}@endif</li>
                @empty
                    <li class="list-none" style="margin-left:-1.25rem;color:var(--muted, #666)">Услуги не прикреплены.</li>
                @endforelse
            </ul>
        </div>
    @endif
    <div class="page-footer">
        <a href="{{ route($entityKey.'.edit', $item->id) }}" class="link-accent">Редактировать</a>
        <span class="divider-text">|</span>
        <a href="{{ route($entityKey.'.index') }}" class="link-accent">К списку</a>
    </div>
@endsection
