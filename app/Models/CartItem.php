<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
    'cart_id',
    'product_id',
    'quantity',
    'price',
    'is_selected', 
];

protected $casts = [
    'price' => 'decimal:2',
    'is_selected' => 'boolean', 
];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
