<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyRepairOrder extends Model
{
    protected $fillable = ['vehicle_id', 'client_id', 'description', 'started_at', 'status_id'];

    protected function casts(): array
    {
        return ['started_at' => 'date'];
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
