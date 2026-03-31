<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $fillable = [
        'client_id', 'brand', 'model', 'vin', 'plate_number', 'year', 'status_id',
    ];

    protected function casts(): array
    {
        return ['year' => 'integer'];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getShortLabelAttribute(): string
    {
        $p = $this->plate_number ? ' ['.$this->plate_number.']' : '';

        return trim($this->brand.' '.$this->model.$p);
    }
}
