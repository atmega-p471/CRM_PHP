<?php

namespace App\Http\Controllers;

use App\Models\Supply;

class SupplyController extends AbstractEntityController
{
    protected static function modelClass(): string
    {
        return Supply::class;
    }

    protected static function entityKey(): string
    {
        return 'supplies';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Автосалон: поставки',
            'index' => [
                'supplier_name' => 'Поставщик',
                'cost' => 'Сумма',
                'received_at' => 'Дата поступления',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'supplier_name' => 'Поставщик',
                'car_description' => 'Автомобиль (текст)',
                'cost' => 'Стоимость',
                'received_at' => 'Дата поступления (YYYY-MM-DD)',
                'notes' => 'Примечание',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'supplier_name' => ['required', 'string', 'max:255'],
            'car_description' => ['nullable', 'string', 'max:2000'],
            'cost' => ['required', 'numeric', 'min:0'],
            'received_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status_id' => $this->statusIdRule(),
        ];
    }
}
