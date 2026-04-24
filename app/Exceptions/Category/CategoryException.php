<?php

namespace App\Exceptions\Category;

use App\Enums\Category\CategoryExceptionsEnum;
use App\Exceptions\BaseException;

class CategoryException extends BaseException
{
    public static function duplicateCategoryName(): self
    {
        throw new self(
            CategoryExceptionsEnum::DUPLICATE_NAME->value,
            422,
            CategoryExceptionsEnum::DUPLICATE_NAME_CODE->value
        );
    }

    public static function categoryNotFound(): self
    {
        throw new self(
            CategoryExceptionsEnum::NOT_FOUND->value,
            404,
            CategoryExceptionsEnum::NOT_FOUND_CODE->value
        );
    }

    public static function invalidParent(): self
    {
        throw new self(
            CategoryExceptionsEnum::INVALID_PARENT->value,
            404,
            CategoryExceptionsEnum::INVALID_PARENT_CODE->value,
        );
    }
}