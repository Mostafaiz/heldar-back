<?php

namespace App\Exceptions\Gallery;

use App\Enums\Gallery\FolderExceptionsEnum;
use App\Exceptions\BaseException;

class FolderException extends BaseException
{
    public static function duplicateFolderName(): self
    {
        throw new self(
            FolderExceptionsEnum::DUPLICATE_NAME->value,
            422,
            FolderExceptionsEnum::DUPLICATE_NAME_CODE->value
        );
    }

    public static function folderNotFound(): self
    {
        throw new self(
            FolderExceptionsEnum::NOT_FOUND->value,
            404,
            FolderExceptionsEnum::NOT_FOUND_CODE->value
        );
    }
}