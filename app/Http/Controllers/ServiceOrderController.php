<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;

class ServiceOrderController extends AbstractEntityController
{
    protected static function indexWith(): array
    {
        return ['status', 'vehicle', 'client'];
    }

    protected static function modelClass(): string
    {
        return ServiceOrder::class;
    }

    protected static function entityKey(): string
    {
        return 'service_orders';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Сервис: заказ-наряды',
            'index' => [
                'vehicle_id' => 'Автомобиль',
                'opened_at' => 'Открыт',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'vehicle_id' => 'Автомобиль',
                'client_id' => 'Клиент',
                'description' => 'Описание работ',
                'opened_at' => 'Дата открытия (YYYY-MM-DD)',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'description' => ['nullable', 'string', 'max:5000'],
            'opened_at' => ['required', 'date'],
            'status_id' => $this->statusIdRule(),
        ];
    }

    public function show(string $id)
    {
        $item = ServiceOrder::query()->with(['status', 'vehicle', 'client'])->findOrFail($id);

        return view('crud.show', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }
}
