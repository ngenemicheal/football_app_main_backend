<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'fan_id', 
        'product_id', 
        'quantity', 
        'total_price', 
        'status'
    ];

    public function fan()
    {
        return $this->belongsTo(Fan::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
