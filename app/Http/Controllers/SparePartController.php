<?php

namespace App\Http\Controllers;

use App\Models\SparePart;

class SparePartController extends AbstractEntityController
{
    protected static function modelClass(): string
    {
        return SparePart::class;
    }

    protected static function entityKey(): string
    {
        return 'spare_parts';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Сервис: запчасти',
            'index' => [
                'name' => 'Название',
                'sku' => 'Артикул',
                'price' => 'Цена',
                'stock_qty' => 'Остаток',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'name' => 'Название',
                'sku' => 'Артикул',
                'price' => 'Цена',
                'stock_qty' => 'Остаток на складе',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:128'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_qty' => ['required', 'integer', 'min:0'],
            'status_id' => $this->statusIdRule(),
        ];
    }
}
