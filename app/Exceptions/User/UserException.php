<?php

namespace App\Exceptions\User;

use App\Enums\User\UserExceptionsEnum;
use App\Exceptions\BaseException;

class UserException extends BaseException
{
    public static function userNotFound(): self
    {
        throw new self(
            UserExceptionsEnum::NOT_FOUND->value,
            404,
            UserExceptionsEnum::NOT_FOUND_CODE->value
        );
    }

    public static function userAlreadyManager(): self
    {
        throw new self(
            UserExceptionsEnum::ALREADY_MANAGER->value,
            400,
            UserExceptionsEnum::ALREADY_MANAGER_CODE->value
        );
    }
}
