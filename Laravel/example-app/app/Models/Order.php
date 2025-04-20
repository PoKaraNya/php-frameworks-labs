<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'status',
        'customer_id',
    ];

    protected $casts = [
        'order_date' => 'datetime',
    ];

    /**
     * Замовлення належить користувачу (покупцю)
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Замовлення має багато позицій (товарів)
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Замовлення має багато відправлень
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
