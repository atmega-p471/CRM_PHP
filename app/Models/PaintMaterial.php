<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaintMaterial extends Model
{
    protected $fillable = ['name', 'unit', 'stock', 'price_per_unit', 'status_id'];

    protected function casts(): array
    {
        return [
            'stock' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
