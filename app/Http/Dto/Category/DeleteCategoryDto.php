<?php

namespace App\Http\Dto\Category;

use App\Models\Category;

class DeleteCategoryDto
{
    public function __construct(public Category $category)
    {
    }
    public static function makeDto(Category $category): DeleteCategoryDto
    {
        return new self(
            category: $category
        );
    }
}