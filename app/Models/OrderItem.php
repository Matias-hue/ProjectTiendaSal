<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'quantity', 'precio'];

    public function product()
    {
        return $this->belongsTo(Producto::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}