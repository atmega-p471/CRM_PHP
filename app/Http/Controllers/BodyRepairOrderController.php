<?php

namespace App\Http\Controllers;

use App\Models\BodyRepairOrder;

class BodyRepairOrderController extends AbstractEntityController
{
    protected static function indexWith(): array
    {
        return ['status', 'vehicle', 'client'];
    }

    protected static function modelClass(): string
    {
        return BodyRepairOrder::class;
    }

    protected static function entityKey(): string
    {
        return 'body_repair_orders';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Кузов: заказы',
            'index' => [
                'vehicle_id' => 'Автомобиль',
                'started_at' => 'Начало',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'vehicle_id' => 'Автомобиль',
                'client_id' => 'Клиент',
                'description' => 'Описание',
                'started_at' => 'Дата начала (YYYY-MM-DD)',
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
            'started_at' => ['required', 'date'],
            'status_id' => $this->statusIdRule(),
        ];
    }

    public function show(string $id)
    {
        $item = BodyRepairOrder::query()->with(['status', 'vehicle', 'client'])->findOrFail($id);

        return view('crud.show', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }
}
