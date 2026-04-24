<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'folders';

    protected $fillable = [
        'name',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function morphParent()
    {
        return $this->hasOneThrough(
            Folder::class,
            Folderable::class,
            'folderable_id',
            'id',
            'id',
            'folder_id'
        )->where('folderable_type', static::class)
            ->whereNull('folders.deleted_at');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function morphChildren()
    {
        return $this->morphedByMany(Folder::class, 'folderable')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(File::class, 'folder_id');
    }

    public function morphImages()
    {
        return $this->morphedByMany(File::class, 'folderable')->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(Folderable::class, 'folder_id');
    }

    public function deleteCompletely()
    {
        foreach ($this->children as $child)
            $child->deleteCompletely();

        $this->items()->delete();
        Folderable::where('folderable_id', $this->id)
            ->where('folderable_type', self::class)
            ->delete();

        $this->delete();
    }
}