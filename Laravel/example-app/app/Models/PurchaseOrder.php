<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class PurchaseOrder extends Model
{
    /**
     * @var string
     */
    protected $table = "purchase_orders";

    /**
     * @var string[]
     */
    protected $fillable = [
        'supplier_id',
        'order_date',
        'status',
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
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return HasMany
     */
    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
