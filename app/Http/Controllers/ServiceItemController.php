<?php

namespace App\Http\Controllers;

use App\Models\ServiceItem;

class ServiceItemController extends AbstractEntityController
{
    protected static function modelClass(): string
    {
        return ServiceItem::class;
    }

    protected static function entityKey(): string
    {
        return 'service_items';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Сервис: услуги',
            'index' => [
                'name' => 'Название',
                'price' => 'Цена',
                'duration_hours' => 'Часы',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'name' => 'Название услуги',
                'price' => 'Цена',
                'duration_hours' => 'Длительность, часы',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_hours' => ['nullable', 'numeric', 'min:0'],
            'status_id' => $this->statusIdRule(),
        ];
    }
}
