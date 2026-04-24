<?php

namespace App\Exceptions\Size;

use App\Enums\Size\SizeExceptionsEnum;
use App\Exceptions\BaseException;

class SizeException extends BaseException
{
    public static function duplicateSizeName(): self
    {
        throw new self(
            SizeExceptionsEnum::DUPLICATE_NAME->value,
            422,
            SizeExceptionsEnum::DUPLICATE_NAME_CODE->value
        );
    }

    public static function sizeNotFound(): self
    {
        throw new self(
            SizeExceptionsEnum::NOT_FOUND->value,
            404,
            SizeExceptionsEnum::NOT_FOUND_CODE->value
        );
    }
}
