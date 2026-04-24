<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'insurances';

    protected $fillable = [
        'name',
        'provider',
        'insurance_code',
        'duration',
        'description',
        'price',
    ];

    public function patterns()
    {
        return $this->belongsToMany(Pattern::class);
    }
}
