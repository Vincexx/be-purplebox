<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        "user_id",
        "product_id",
        "quantity",
        "unit_price",
        "total_price",
        "status",
        "delivery_address",
        "delivery_date",
        "message",
        "type",
        "image",
        "remarks",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
