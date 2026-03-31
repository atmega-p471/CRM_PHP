<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            'clients' => [
                ['slug' => 'new', 'title' => 'Новый'],
                ['slug' => 'active', 'title' => 'Активный'],
                ['slug' => 'archived', 'title' => 'Архив'],
            ],
            'vehicles' => [
                ['slug' => 'in_stock', 'title' => 'На складе'],
                ['slug' => 'in_service', 'title' => 'В сервисе'],
                ['slug' => 'in_paint', 'title' => 'В кузовном'],
                ['slug' => 'sold', 'title' => 'Продан'],
            ],
            'supplies' => [
                ['slug' => 'ordered', 'title' => 'Заказан'],
                ['slug' => 'received', 'title' => 'Принят'],
                ['slug' => 'cancelled', 'title' => 'Отменён'],
            ],
            'sales' => [
                ['slug' => 'pending', 'title' => 'Ожидает'],
                ['slug' => 'completed', 'title' => 'Завершена'],
                ['slug' => 'cancelled', 'title' => 'Отменена'],
            ],
            'service_orders' => [
                ['slug' => 'open', 'title' => 'Открыт'],
                ['slug' => 'in_progress', 'title' => 'В работе'],
                ['slug' => 'completed', 'title' => 'Завершён'],
                ['slug' => 'cancelled', 'title' => 'Отменён'],
            ],
            'spare_parts' => [
                ['slug' => 'active', 'title' => 'Активна'],
                ['slug' => 'discontinued', 'title' => 'Снята'],
            ],
            'service_items' => [
                ['slug' => 'active', 'title' => 'Активна'],
                ['slug' => 'inactive', 'title' => 'Неактивна'],
            ],
            'body_repair_orders' => [
                ['slug' => 'new', 'title' => 'Новый'],
                ['slug' => 'in_progress', 'title' => 'В работе'],
                ['slug' => 'completed', 'title' => 'Завершён'],
                ['slug' => 'cancelled', 'title' => 'Отменён'],
            ],
            'paint_materials' => [
                ['slug' => 'in_stock', 'title' => 'В наличии'],
                ['slug' => 'low', 'title' => 'Заканчивается'],
                ['slug' => 'out_of_stock', 'title' => 'Нет'],
            ],
            'users' => [
                ['slug' => 'active', 'title' => 'Работает'],
                ['slug' => 'vacation', 'title' => 'Отпуск'],
                ['slug' => 'dismissed', 'title' => 'Уволен'],
            ],
        ];

        foreach ($rows as $entityKey => $statuses) {
            foreach ($statuses as $st) {
                Status::query()->updateOrCreate(
                    ['entity_key' => $entityKey, 'slug' => $st['slug']],
                    ['title' => $st['title']]
                );
            }
        }
    }
}
