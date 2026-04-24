<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pattern extends Model
{
    protected $table = 'patterns';

    protected $fillable = [
        'name',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function files()
    {
        return $this->morphToMany(File::class, 'fileable');
    }

    public function firstImage()
    {
        return $this->morphToMany(File::class, 'fileable')->first();
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    public function guarantees()
    {
        return $this->belongsToMany(Guarantee::class);
    }

    public function insurances()
    {
        return $this->belongsToMany(Insurance::class);
    }
}
