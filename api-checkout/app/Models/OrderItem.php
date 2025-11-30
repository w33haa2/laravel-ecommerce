<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Added for HasFactory trait

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'name', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
