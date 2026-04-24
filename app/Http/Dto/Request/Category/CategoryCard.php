<?php

namespace App\Http\Dto\Request\Category;

use App\Http\Dto\Response\Customer\Image as ImageDto;
use App\Utils\BaseWireableDto;

readonly class CategoryCard extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?ImageDto $image,
        public ?int $parent_id,
    ) {}

    public static function from($array)
    {
        return new self(
            id: $array->id,
            name: $array->name,
            image: $array->image->first() ? ImageDto::from($array->image->first()) : null,
            parent_id: $array->parent_id,
        );
    }
}