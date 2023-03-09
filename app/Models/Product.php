<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $fillable = [
        "name",
        "type",
        "image",
        "description",
        "price",
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
