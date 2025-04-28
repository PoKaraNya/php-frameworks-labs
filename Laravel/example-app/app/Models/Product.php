<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Product extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "products";

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'supplier_id',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

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
    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
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
    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
