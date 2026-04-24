<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;
    protected $table = 'colors';

    protected $fillable = [
        'name',
        'code'
    ];

    public function patterns()
    {
        return $this->belongsToMany(Pattern::class);
    }
}
