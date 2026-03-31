<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceItem extends Model
{
    protected $fillable = ['name', 'price', 'duration_hours', 'status_id'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_hours' => 'decimal:2',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
