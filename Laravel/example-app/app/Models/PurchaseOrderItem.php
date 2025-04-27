<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class PurchaseOrderItem extends Model
{
    /**
     * @var string
     */
    protected $table = "purchase_order_items";

    /**
     * @var string[]
     */
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity',
        'price_per_unit',
    ];

    /**
     * @return BelongsTo
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
