<?php

namespace App\Http\Dto\Response\Customer\Category;

use App\Http\Dto\Response\Customer\Image as ImageDto;
use App\Utils\BaseWireableDto;

readonly class CategoryWithChildren extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?ImageDto $image,
        public ?array $children,
        public ?int $parent_id,
    ) {}

    public static function from($category)
    {
        return new self(
            id: $category->id,
            name: $category->name,
            image: $category->image->first()
                ? ImageDto::from($category->image->first())
                : null,
            children: $category->children->isNotEmpty()
                ? $category->children->map(fn($child) => self::from($child))->toArray()
                : null,
            parent_id: $category->parent_id,
        );
    }
}
