<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ClientController extends AbstractEntityController
{
    protected static function modelClass(): string
    {
        return Client::class;
    }

    protected static function entityKey(): string
    {
        return 'clients';
    }

    protected static function cfg(): array
    {
        return [
            'label' => 'Клиенты',
            'index' => [
                'name' => 'Имя',
                'phone' => 'Телефон',
                'email' => 'Email',
                'status_id' => 'Статус',
            ],
            'fields' => [
                'name' => 'ФИО или название',
                'phone' => 'Телефон',
                'email' => 'Email',
                'address' => 'Адрес',
                'status_id' => 'Статус',
            ],
        ];
    }

    protected function validationRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'status_id' => $this->statusIdRule(),
        ];
    }
}
