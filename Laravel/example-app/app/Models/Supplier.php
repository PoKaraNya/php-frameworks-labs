<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class Supplier extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "suppliers";

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'contact_name',
        'contact_phone',
        'contact_email',
        'address',
    ];

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return HasMany
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
