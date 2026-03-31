<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supply extends Model
{
    protected $fillable = [
        'supplier_name', 'car_description', 'cost', 'received_at', 'notes', 'status_id',
    ];

    protected function casts(): array
    {
        return [
            'cost' => 'decimal:2',
            'received_at' => 'datetime',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
