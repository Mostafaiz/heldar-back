<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeGroup extends Model
{
    use SoftDeletes;

    protected $table = 'attribute_groups';

    protected $fillable = [
        'name'
    ];

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function (AttributeGroup $attributeGroup) {
            $attributeGroup->attributes()->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
