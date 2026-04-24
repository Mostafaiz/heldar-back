<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'price',
        'discount',
        'sku',
        'quantity',
        'size_id',
        'pattern_id',
        'guarantee_id',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function pattern()
    {
        return $this->belongsTo(Pattern::class);
    }

    public function discountedPrice()
    {
        return $this->price - ($this->discount ?? 0);
    }

    public function demands()
    {
        return $this->hasMany(Demand::class);
    }
}
