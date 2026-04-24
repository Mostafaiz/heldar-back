<?php

namespace App\Http\Dto\Category;

use App\Models\Category;
use App\Support\undefined;

class UpdateCategoryDto
{
    public function __construct(
        public Category $category,
        public string|undefined $name,
        public string|null|undefined $descriptionCategory,
        public string|null|undefined $descriptionPage,
        public Category|null|undefined $parent
    ) {
    }
    public static function makeDto(
        Category $category,
        $array,
        Category|null|undefined $parent = new undefined()
    ): UpdateCategoryDto {
        return new self(
            category: $category,
            name: array_key_exists('name', $array) ? $array['name'] : new undefined(),
            descriptionCategory: array_key_exists('descriptionCategory', $array) ? $array['descriptionCategory'] : new undefined(),
            descriptionPage: array_key_exists('descriptionPage', $array) ? $array['descriptionPage'] : new undefined(),
            parent: $parent
        );
    }
}