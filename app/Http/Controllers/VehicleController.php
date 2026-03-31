<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

class VehicleController extends AbstractEntityController
{
    protected static function indexWith(): array
    {
        return ['status', 'client'];
    }

    protected static function modelClass(): string
    {
        return Vehicle::class;
    }

    protected static function entityKey(): string
    {
        return 'vehicles';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Автомобили',
            'index' => [
                'client_id' => 'Клиент',
                'brand' => 'Марка',
                'model' => 'Модель',
                'plate_number' => 'Номер',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'client_id' => 'Клиент',
                'brand' => 'Марка',
                'model' => 'Модель',
                'vin' => 'VIN',
                'plate_number' => 'Гос. номер',
                'year' => 'Год',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'brand' => ['required', 'string', 'max:128'],
            'model' => ['required', 'string', 'max:128'],
            'vin' => ['nullable', 'string', 'max:64'],
            'plate_number' => ['nullable', 'string', 'max:32'],
            'year' => ['nullable', 'integer', 'min:1970', 'max:2100'],
            'status_id' => $this->statusIdRule(),
        ];
    }

    public function show(string $id)
    {
        $item = Vehicle::query()->with(['status', 'client'])->findOrFail($id);

        return view('crud.show', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }
}
