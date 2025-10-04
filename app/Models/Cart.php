<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function getItemCountAttribute()
    {
        return $this->items->sum('quantity');
    }
    
    public function getSelectedTotalAttribute()
{
    return $this->items()->where('is_selected', true)->get()->sum(function ($item) {
        return $item->price * $item->quantity;
    });
}

public function getSelectedItemCountAttribute()
{
    return $this->items()->where('is_selected', true)->sum('quantity');
}

public function selectedItems()
{
    return $this->items()->where('is_selected', true);
}
}

