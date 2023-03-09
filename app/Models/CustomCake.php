<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomCake extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        "user_id",
        "quantity",
        "message",
        "remarks",
        "image",
        "price",
        "status",
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
