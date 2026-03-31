<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use Illuminate\Http\Request;

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
                'service_item_ids' => 'Услуги в наряде',
                'opened_at' => 'Дата и время открытия',
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
            'service_item_ids' => ['nullable', 'array'],
            'service_item_ids.*' => ['integer', 'exists:service_items,id'],
            'opened_at' => ['required', 'date'],
            'status_id' => $this->statusIdRule(),
        ];
    }

    public function edit(string $id)
    {
        $item = ServiceOrder::query()->with('serviceItems')->findOrFail($id);

        return view('crud.form', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->validationRules());
        $ids = $data['service_item_ids'] ?? [];
        unset($data['service_item_ids']);
        $order = ServiceOrder::query()->create($data);
        $order->serviceItems()->sync($this->pivotForServiceItems($ids));

        return redirect()->route(static::entityKey().'.index')->with('ok', 'Запись создана.');
    }

    public function update(Request $request, string $id)
    {
        $item = ServiceOrder::query()->findOrFail($id);
        $data = $request->validate($this->validationRules());
        $ids = $data['service_item_ids'] ?? [];
        unset($data['service_item_ids']);
        $item->update($data);
        $item->serviceItems()->sync($this->pivotForServiceItems($ids));

        return redirect()->route(static::entityKey().'.index')->with('ok', 'Запись обновлена.');
    }

    private function pivotForServiceItems(array $itemIds): array
    {
        $map = [];
        foreach ($itemIds as $itemId) {
            $map[(int) $itemId] = ['quantity' => 1];
        }

        return $map;
    }

    public function show(string $id)
    {
        $item = ServiceOrder::query()->with(['status', 'vehicle', 'client', 'serviceItems'])->findOrFail($id);

        return view('crud.show', [
            'entityKey' => static::entityKey(),
            'cfg' => static::cfg(),
            'item' => $item,
        ]);
    }
}
