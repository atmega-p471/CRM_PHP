<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['entity_key', 'slug', 'title'];

    public static function forEntityKey(string $entityKey): Builder
    {
        return static::query()->where('entity_key', $entityKey)->orderBy('id');
    }
}
