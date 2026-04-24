<?php

namespace App\Http\Dto\Response;

use App\Utils\BaseWireableDto;
use \App\Models\Category as CategoryModel;
use App\Models\File;
use Carbon\Carbon;

readonly class Category extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $descriptionCategory,
        public ?string $descriptionPage,
        public ?File $image,
        public ?int $parentId,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {
    }

    public static function from(CategoryModel $category)
    {
        return new self(
            id: $category->id,
            name: $category->name,
            descriptionCategory: $category->description_category,
            descriptionPage: $category->description_page,
            image: $category->image->first(),
            parentId: $category->parent_id,
            createdAt: $category->created_at,
            updatedAt: $category->updated_at,
        );
    }
}