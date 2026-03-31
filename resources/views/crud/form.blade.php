@extends('layouts.app')

@section('content')
    <h1 class="page-title">{{ $item->exists ? 'Редактирование' : 'Создание' }}: {{ $cfg['label'] }}</h1>
    <form method="POST" action="{{ $item->exists ? route($entityKey.'.update', $item->id) : route($entityKey.'.store') }}" class="form-card max-w-2xl">
        @csrf
        @if($item->exists) @method('PUT') @endif
        <div class="stack">
        @foreach($cfg['fields'] as $field => $fieldLabel)
            @if($entityKey === 'users' && $field === 'password' && $item->exists)
                <div class="field">
                    <label class="field-label">{{ $fieldLabel }} (оставьте пустым, чтобы не менять)</label>
                    <input type="password" name="{{ $field }}" class="form-input" value="" autocomplete="new-password">
                    <input type="password" name="password_confirmation" placeholder="Повтор нового пароля" class="form-input mt-2" value="" autocomplete="new-password">
                </div>
            @else
            <div class="field">
                <label class="field-label">{{ $fieldLabel }}</label>
                @if($field === 'description' || $field === 'address' || $field === 'notes' || $field === 'car_description')
                    <textarea name="{{ $field }}" rows="3" class="form-textarea">{{ old($field, $item->{$field}) }}</textarea>
                @elseif($field === 'password' && $entityKey === 'users')
                    <input type="password" name="{{ $field }}" class="form-input" value="{{ old($field, '') }}" autocomplete="new-password" @if(!$item->exists) required @endif>
                    <input type="password" name="password_confirmation" placeholder="Повтор пароля" class="form-input mt-2" value="" autocomplete="new-password" @if(!$item->exists) required @endif>
                @elseif($field === 'status_id')
                    <select name="status_id" class="form-select" required>
                        <option value="" disabled @selected(old('status_id', $item->status_id) === null)>— выберите статус —</option>
                        @foreach(\App\Models\Status::forEntityKey($entityKey)->get() as $st)
                            <option value="{{ $st->id }}" @selected((string) old('status_id', $item->status_id) === (string) $st->id)>{{ $st->title }}</option>
                        @endforeach
                    </select>
                @elseif($field === 'service_item_ids' && $entityKey === 'service_orders')
                    @php
                        $selectedItems = old('service_item_ids', $item->exists ? $item->serviceItems->pluck('id')->all() : []);
                    @endphp
                    <select name="service_item_ids[]" class="form-select" multiple size="8">
                        @foreach(\App\Models\ServiceItem::query()->with('status')->orderBy('name')->get() as $si)
                            <option value="{{ $si->id }}" @selected(in_array((string) $si->id, array_map('strval', (array) $selectedItems), true))>
                                {{ $si->name }} — {{ $si->price }} ₽
                            </option>
                        @endforeach
                    </select>
                @elseif($field === 'client_id')
                    <select name="client_id" class="form-select" required>
                        <option value="" disabled @selected(old('client_id', $item->client_id) === null)>— клиент —</option>
                        @foreach(\App\Models\Client::query()->orderBy('name')->get() as $c)
                            <option value="{{ $c->id }}" @selected((string) old('client_id', $item->client_id) === (string) $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                @elseif($field === 'vehicle_id')
                    <select name="vehicle_id" class="form-select" required>
                        <option value="" disabled @selected(old('vehicle_id', $item->vehicle_id) === null)>— автомобиль —</option>
                        @foreach(\App\Models\Vehicle::query()->with('client')->orderByDesc('id')->get() as $v)
                            <option value="{{ $v->id }}" @selected((string) old('vehicle_id', $item->vehicle_id) === (string) $v->id)>
                                {{ $v->short_label }} — {{ $v->client?->name }}
                            </option>
                        @endforeach
                    </select>
                @elseif($field === 'role' && $entityKey === 'users')
                    <select name="role" class="form-select" required>
                        @foreach(['manager' => 'Менеджер', 'master' => 'Мастер', 'mechanic' => 'Механик', 'painter' => 'Маляр'] as $val => $title)
                            <option value="{{ $val }}" @selected(old('role', $item->role) === $val)>{{ $title }}</option>
                        @endforeach
                    </select>
                @elseif($field === 'email')
                    <input type="email" name="{{ $field }}" class="form-input" value="{{ old($field, $item->{$field}) }}">
                @elseif($field === 'year' || $field === 'stock_qty')
                    <input type="number" name="{{ $field }}" class="form-input" value="{{ old($field, $item->{$field}) }}">
                @elseif(in_array($field, ['cost', 'sale_price', 'price', 'duration_hours', 'stock', 'price_per_unit'], true))
                    <input type="number" step="0.01" name="{{ $field }}" class="form-input" value="{{ old($field, $item->{$field}) }}">
                @elseif(in_array($field, ['opened_at', 'started_at', 'received_at', 'sold_at'], true))
                    <input type="datetime-local" name="{{ $field }}" class="form-input" step="60" value="{{ old($field, $item->{$field} ? $item->{$field}->format('Y-m-d\TH:i') : '') }}">
                @else
                    <input name="{{ $field }}" class="form-input" value="{{ old($field, $item->{$field}) }}">
                @endif
            </div>
            @endif
        @endforeach
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route($entityKey.'.index') }}" class="btn">Отмена</a>
        </div>
    </form>
@endsection
