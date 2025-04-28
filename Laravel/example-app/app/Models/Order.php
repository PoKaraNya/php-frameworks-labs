<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Order extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "orders";

    /**
     * @var string[]
     */
    protected $fillable = [
        'order_date',
        'status',
        'customer_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'order_date' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
