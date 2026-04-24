<?php

namespace App\Models;

use App\Enums\Gallery\ImageTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slides';

    protected $fillable = [
        'link',
        'status',
        'level',
    ];

    public function images()
    {
        return $this->morphToMany(File::class, 'fileable')->withPivot('type')->withTimestamps();
    }

    public function desktopImage()
    {
        return $this->images()->wherePivot('type', ImageTypeEnum::DESKTOP_SLIDE->value);
    }

    public function mobileImage()
    {
        return $this->images()->wherePivot('type', ImageTypeEnum::MOBILE_SLIDE->value);
    }
}
