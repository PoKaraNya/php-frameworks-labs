<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{

    use HasFactory;

    protected $table = 'test'; // Вказуємо власну таблицю

    protected $fillable = ['name', 'email', 'password']; // Дозволені для масового призначення поля

}
