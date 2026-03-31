<?php

namespace App\Http\Controllers;

use App\Models\PaintMaterial;

class PaintMaterialController extends AbstractEntityController
{
    protected static function modelClass(): string
    {
        return PaintMaterial::class;
    }

    protected static function entityKey(): string
    {
        return 'paint_materials';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Кузов: материалы',
            'index' => [
                'name' => 'Название',
                'unit' => 'Ед.',
                'stock' => 'Остаток',
                'price_per_unit' => 'Цена за ед.',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'name' => 'Название',
                'unit' => 'Единица (л, кг, шт)',
                'stock' => 'Остаток',
                'price_per_unit' => 'Цена за единицу',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:32'],
            'stock' => ['required', 'numeric', 'min:0'],
            'price_per_unit' => ['required', 'numeric', 'min:0'],
            'status_id' => $this->statusIdRule(),
        ];
    }
}
