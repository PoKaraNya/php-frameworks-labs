<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'contact_phone',
        'contact_email',
        'address',
    ];

    /**
     * Постачальник має багато продуктів
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Постачальник має багато закупівельних замовлень
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
