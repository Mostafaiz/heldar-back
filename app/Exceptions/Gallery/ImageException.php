<?php

namespace App\Exceptions\Gallery;

use App\Enums\Gallery\ImageExceptionsEnum;
use App\Exceptions\BaseException;

class ImageException extends BaseException
{
    public static function imageNotFound(): self
    {
        throw new self(
            ImageExceptionsEnum::NOT_FOUND->value,
            404,
            ImageExceptionsEnum::NOT_FOUND_CODE->value
        );
    }

    public static function desktopImageNotFound(): self
    {
        throw new self(
            ImageExceptionsEnum::DESKTOP_IMAGE_NOT_FOUND->value,
            404,
            ImageExceptionsEnum::DESKTOP_IMAGE_NOT_FOUND_CODE->value
        );
    }

    public static function mobileImageNotFound(): self
    {
        throw new self(
            ImageExceptionsEnum::MOBILE_IMAGE_NOT_FOUND->value,
            404,
            ImageExceptionsEnum::MOBILE_IMAGE_NOT_FOUND_CODE->value
        );
    }
}
