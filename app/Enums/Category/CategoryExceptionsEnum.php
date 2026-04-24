<?php

namespace App\Enums\Category;

enum CategoryExceptionsEnum: string
{
    case DUPLICATE_NAME = 'A category with this name already exists';
    case DUPLICATE_NAME_CODE = '422|1';
    case NOT_FOUND = 'Category not found';
    case NOT_FOUND_CODE = '404|1';
    case INVALID_PARENT = 'invalid parent';
    case INVALID_PARENT_CODE = '404|2';
}