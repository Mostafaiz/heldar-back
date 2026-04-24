<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{

    protected $table = 'files';

    protected $fillable = [
        'name',
        'alt',
        'mime_type',
        'path',
        'folder_id',
    ];

    protected function fullname(): Attribute
    {
        return Attribute::get(fn() => $this->name . '.' . $this->mime_type);
    }

    public function folders()
    {
        return $this->morphToMany(Folder::class, 'folderable')->withTimestamps();
    }

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'fileable');
    }

    public function patterns()
    {
        return $this->morphedByMany(Pattern::class, 'fileable');
    }

    public function slides()
    {
        return $this->morphedByMany(Slide::class, 'fileable')
            ->withPivot('type')
            ->withTimestamps();
    }
}
