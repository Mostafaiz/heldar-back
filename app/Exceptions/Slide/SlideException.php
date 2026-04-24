<?php

namespace App\Exceptions\Slide;

use App\Enums\Slide\SlideExceptionsEnum;
use App\Exceptions\BaseException;

class SlideException extends BaseException
{
    public static function NotFound(): self
    {
        throw new self(
            SlideExceptionsEnum::NOT_FOUND->value,
            404,
            SlideExceptionsEnum::NOT_FOUND_CODE->value
        );
    }
}
