<?php

namespace App\Http\Controllers;

use App\Models\Sale;

class SaleController extends AbstractEntityController
{
    protected static function indexWith(): array
    {
        return ['status', 'vehicle', 'client'];
    }

    protected static function modelClass(): string
    {
        return Sale::class;
    }

    protected static function entityKey(): string
    {
        return 'sales';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Автосалон: продажи',
            'index' => [
                'vehicle_id' => 'Автомобиль',
                'client_id' => 'Клиент',
                'sale_price' => 'Цена',
                'sold_at' => 'Дата продажи',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'vehicle_id' => 'Автомобиль',
                'client_id' => 'Клиент',
                'sale_price' => 'Цена продажи',
                'sold_at' => 'Дата и время продажи',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'sold_at' => ['required', 'date'],
            'status_id' => $this->statusIdRule(),
        ];
    }

    public function show(string $id)
    {
        $item = Sale::query()->with(['status', 'vehicle', 'client'])->findOrFail($id);

        return view('crud.show', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }
}
