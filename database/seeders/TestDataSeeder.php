<?php

namespace Database\Seeders;

use App\Models\BodyRepairOrder;
use App\Models\Client;
use App\Models\PaintMaterial;
use App\Models\Sale;
use App\Models\ServiceItem;
use App\Models\ServiceOrder;
use App\Models\SparePart;
use App\Models\Status;
use App\Models\Supply;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use RuntimeException;

class TestDataSeeder extends Seeder
{
    private function statusId(string $entityKey, string $slug): int
    {
        $id = Status::query()
            ->where('entity_key', $entityKey)
            ->where('slug', $slug)
            ->value('id');

        if ($id === null) {
            throw new RuntimeException("Не найден статус: {$entityKey} / {$slug}");
        }

        return (int) $id;
    }

    public function run(): void
    {
        if (Client::query()->exists()) {
            return;
        }

        $now = Carbon::now();

        $client1 = Client::query()->create([
            'name' => 'Иванов Пётр Сергеевич',
            'phone' => '+7 (900) 111-22-33',
            'email' => 'client1@example.test',
            'address' => 'г. Москва, ул. Примерная, д. 1',
            'status_id' => $this->statusId('clients', 'active'),
        ]);

        $client2 = Client::query()->create([
            'name' => 'ООО «АвтоЛайн»',
            'phone' => '+7 (495) 000-00-01',
            'email' => 'zakaz@avtolain.test',
            'address' => 'МО, склад №3',
            'status_id' => $this->statusId('clients', 'new'),
        ]);

        $v1 = Vehicle::query()->create([
            'client_id' => $client1->id,
            'brand' => 'Toyota',
            'model' => 'Camry',
            'vin' => 'XW8ZZZ6XZJG000001',
            'plate_number' => 'А001АА777',
            'year' => 2022,
            'status_id' => $this->statusId('vehicles', 'in_service'),
        ]);

        $v2 = Vehicle::query()->create([
            'client_id' => $client1->id,
            'brand' => 'LADA',
            'model' => 'Vesta',
            'vin' => 'XTA210900L0000001',
            'plate_number' => 'В777ВВ199',
            'year' => 2021,
            'status_id' => $this->statusId('vehicles', 'in_stock'),
        ]);

        $v3 = Vehicle::query()->create([
            'client_id' => $client2->id,
            'brand' => 'Kia',
            'model' => 'Rio',
            'vin' => 'KNADC123456789012',
            'plate_number' => 'С123СС777',
            'year' => 2020,
            'status_id' => $this->statusId('vehicles', 'in_stock'),
        ]);

        $si1 = ServiceItem::query()->create([
            'name' => 'Замена масла ДВС',
            'price' => 3500,
            'duration_hours' => 0.5,
            'status_id' => $this->statusId('service_items', 'active'),
        ]);

        $si2 = ServiceItem::query()->create([
            'name' => 'Диагностика ходовой части',
            'price' => 2500,
            'duration_hours' => 1,
            'status_id' => $this->statusId('service_items', 'active'),
        ]);

        $si3 = ServiceItem::query()->create([
            'name' => 'Сход-развал',
            'price' => 4200,
            'duration_hours' => 1.5,
            'status_id' => $this->statusId('service_items', 'active'),
        ]);

        $order = ServiceOrder::query()->create([
            'vehicle_id' => $v1->id,
            'client_id' => $client1->id,
            'description' => 'ТО-2, проверка подвески',
            'opened_at' => $now->copy()->subDays(2)->setTime(9, 30),
            'status_id' => $this->statusId('service_orders', 'in_progress'),
        ]);
        $order->serviceItems()->sync([
            $si1->id => ['quantity' => 1],
            $si2->id => ['quantity' => 1],
        ]);

        $order2 = ServiceOrder::query()->create([
            'vehicle_id' => $v2->id,
            'client_id' => $client1->id,
            'description' => 'Предпродажная подготовка',
            'opened_at' => $now->copy()->subHours(5),
            'status_id' => $this->statusId('service_orders', 'open'),
        ]);
        $order2->serviceItems()->sync([$si3->id => ['quantity' => 1]]);

        BodyRepairOrder::query()->create([
            'vehicle_id' => $v1->id,
            'client_id' => $client1->id,
            'description' => 'Устранение вмятины заднего крыла',
            'started_at' => $now->copy()->subDay()->setTime(14, 0),
            'status_id' => $this->statusId('body_repair_orders', 'in_progress'),
        ]);

        Supply::query()->create([
            'supplier_name' => 'ООО «Поставка Авто»',
            'car_description' => 'Hyundai Solaris, белый',
            'cost' => 1_850_000,
            'received_at' => $now->copy()->subDays(3)->setTime(11, 15),
            'notes' => 'Документы в архиве каб. 12',
            'status_id' => $this->statusId('supplies', 'received'),
        ]);

        Sale::query()->create([
            'vehicle_id' => $v3->id,
            'client_id' => $client2->id,
            'sale_price' => 1_250_000,
            'sold_at' => $now->copy()->subDays(7)->setTime(16, 45),
            'status_id' => $this->statusId('sales', 'completed'),
        ]);

        SparePart::query()->create([
            'name' => 'Масляный фильтр OEM',
            'sku' => 'OIL-FLT-001',
            'price' => 890,
            'stock_qty' => 24,
            'status_id' => $this->statusId('spare_parts', 'active'),
        ]);

        SparePart::query()->create([
            'name' => 'Тормозные колодки передние',
            'sku' => 'BRK-P-F-02',
            'price' => 4500,
            'stock_qty' => 6,
            'status_id' => $this->statusId('spare_parts', 'active'),
        ]);

        PaintMaterial::query()->create([
            'name' => 'База RAL 9003',
            'unit' => 'л',
            'stock' => 12.5,
            'price_per_unit' => 1200,
            'status_id' => $this->statusId('paint_materials', 'in_stock'),
        ]);
    }
}
