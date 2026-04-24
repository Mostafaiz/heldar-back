<?php

namespace App\Http\Dto\Category;

use App\Models\Category;

class CreateCategoryDto
{
    public function __construct(
        public string $name,
        public ?string $descriptionCategory,
        public ?string $descriptionPage,
        public ?int $imageId,
        public ?int $parentId
    ) {
    }
    public static function makeDto(array $array)
    {
        return new self(
            name: $array['name'],
            descriptionCategory: $array['descriptionCategory'] ?? null,
            descriptionPage: $array['descriptionPage'] ?? null,
            imageId: $array['imageId'],
            parentId: $array['parentId']
        );
    }
}