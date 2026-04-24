<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'pattern_name',
        'sku',
        'unit_price',
        'quantity',
        'total_price',
        'guarantee',
        'insurance'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
