<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Inventory extends Model
{
    /**
     * @var string
     */
    protected $table = "inventories";

    /**
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'last_updated',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'last_updated' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
