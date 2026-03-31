<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceOrder extends Model
{
    protected $fillable = ['vehicle_id', 'client_id', 'description', 'opened_at', 'status_id'];

    protected function casts(): array
    {
        return ['opened_at' => 'datetime'];
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

    public function serviceItems(): BelongsToMany
    {
        return $this->belongsToMany(ServiceItem::class, 'service_order_service_item')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
