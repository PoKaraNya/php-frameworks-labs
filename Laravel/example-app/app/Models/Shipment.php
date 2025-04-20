<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shipment_date',
        'delivery_date',
        'status',
    ];

    protected $casts = [
        'shipment_date' => 'datetime',
        'delivery_date' => 'datetime',
    ];

    /**
     * Відвантаження належить замовленню
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
