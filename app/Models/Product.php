<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'english_name',
        'slug',
        'brand',
        'description',
        'status',
        'level',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function patterns()
    {
        return $this->hasMany(Pattern::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withPivot('value');
    }

    public function attributeGroup()
    {
        return $this->belongsTo(AttributeGroup::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->morphToMany(File::class, 'fileable');
    }

    public function demands()
    {
        return $this->hasMany(Demand::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
