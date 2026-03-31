<?php

namespace Database\Seeders;

use App\Models\Treasury;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(StatusSeeder::class);

        $activeStatusId = \App\Models\Status::query()
            ->where('entity_key', 'users')
            ->where('slug', 'active')
            ->value('id');

        User::query()->updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Администратор',
                'password' => 'password',
                'role' => 'manager',
                'status_id' => $activeStatusId,
            ]
        );

        Treasury::query()->firstOrCreate(['name' => 'main']);
    }
}
