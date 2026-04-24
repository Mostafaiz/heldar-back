<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id', 'copied_from_cart_item_id', 'locked'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function itemsTotal(): int
    {
        return $this->items->sum(fn($item) => $item->total());
    }

    // public function total(): int
    // {
    //     return $this->itemsTotal();
    // }
    // public function total()
    // {
    //     return $this->items->sum(function ($item) {
    //         return $item->variant->finalPrice() * $item->quantity;
    //     });
    // }
    public function total()
    {
        return $this->items->sum(fn($item) => $item->totalPrice());
    }
}
