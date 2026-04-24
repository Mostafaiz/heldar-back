<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folderable extends Model
{

    protected $table = "folderables";

    protected $fillable = [
        "folder_id",
        "folderable_id",
        "folderable_type",
    ];

    public function folderable()
    {
        return $this->morphTo();
    }

    public function images($query)
    {
        return $query->where('folderable_type', File::class);
    }
}
