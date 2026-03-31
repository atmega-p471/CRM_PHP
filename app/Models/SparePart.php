<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SparePart extends Model
{
    protected $fillable = ['name', 'sku', 'price', 'stock_qty', 'status_id'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_qty' => 'integer',
        ];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}
