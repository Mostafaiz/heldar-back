<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_variant_id',
        'quantity',
        'guarantee_id',
        'insurance_id',
        'price',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function guarantee(): BelongsTo
    {
        return $this->belongsTo(Guarantee::class);
    }

    public function insurance(): BelongsTo
    {
        return $this->belongsTo(Insurance::class);
    }

    public function unitPrice()
    {
        $base = $this->variant->price - ($this->variant->discount ?? 0);

        $guarantee = $this->guarantee ? $this->guarantee->price : 0;
        $insurance = $this->insurance ? $this->insurance->price : 0;

        return $base + $guarantee + $insurance;
    }
    public function totalPrice()
    {
        return $this->unitPrice() * $this->quantity;
    }
}
