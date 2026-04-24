<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @property int $id
 * @property string $name
 * @property string|null $description_category
 * @property string|null $description_page
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $categories_id
 *
 * @property Category|null $category
 * @property Collection|Category[] $categories
 *
 * @package App\Models
 */
class Category extends Model
{
    protected $table = 'categories';

    protected $casts = [
        'status' => 'bool',
        'parent_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'description_category',
        'description_page',
        'status',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function childrenRecursive()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('childrenRecursive');
    }

    public function image()
    {
        return $this->morphToMany(File::class, 'fileable')->limit(1)->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
