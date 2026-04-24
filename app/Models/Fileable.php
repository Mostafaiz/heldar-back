<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fileable extends Model
{

    protected $table = "fileables";

    protected $fillable = [
        "file_id",
        "fileable_id",
        "fileable_type",
    ];

    
}