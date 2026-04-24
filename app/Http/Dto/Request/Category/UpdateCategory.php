<?php

namespace App\Http\Dto\Request\Category;

class UpdateCategory
{

    public function __construct(
        public int $id,
        public string $name,
        public ?string $descriptionCategory,
        public ?string $descriptionPage,
        public ?int $imageId,
        public ?int $parentId,
    ) {
    }

    public static function makeDto($array)
    {
        return new self(
            id: $array['id'],
            name: $array['name'],
            descriptionCategory: $array['descriptionCategory'],
            descriptionPage: $array['descriptionPage'],
            imageId: $array['imageId'],
            parentId: $array['parentId'],
        );
    }
}