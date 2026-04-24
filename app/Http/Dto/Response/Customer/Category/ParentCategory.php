<?php

namespace App\Http\Dto\Response\Customer\Category;

use App\Http\Dto\Response\Customer\Image as ImageDto;
use App\Models\Category;
use App\Utils\BaseWireableDto;
use Illuminate\Database\Eloquent\Collection;

readonly class ParentCategory extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?ImageDto $image,
    ) {}

    public static function from($category)
    {
        // dd($category);
        return new self(
            id: $category['id'],
            name: $category->name,
            image: $category->image->first()
                ? ImageDto::from($category->image->first())
                : null,
        );
    }
}
