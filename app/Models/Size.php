<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table = 'sizes';

    protected $fillable = [
        'name',
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function patterns()
    {
        return $this->belongsToMany(Pattern::class);
    }
}
