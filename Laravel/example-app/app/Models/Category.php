<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // Вказуємо дозволені для масового заповнення поля
    protected $fillable = [
        'name',
        'description',
    ];

    // Відношення "один до багатьох" з Product
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
