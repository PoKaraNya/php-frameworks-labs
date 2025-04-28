<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Shipment extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "shipments";

    /**
     * @var string[]
     */
    protected $fillable = [
        'order_id',
        'shipment_date',
        'delivery_date',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'shipment_date' => 'datetime',
        'delivery_date' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
