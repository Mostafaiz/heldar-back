<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guarantee extends Model
{
    protected $table = 'guarantees';

    protected $fillable = [
        'name',
        'provider',
        'serial',
        'duration',
        'description',
        'price',
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
