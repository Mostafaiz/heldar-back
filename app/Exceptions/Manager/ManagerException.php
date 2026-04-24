<?php

namespace App\Exceptions\Manager;

use App\Enums\Manager\ManagerExceptionsEnum;
use App\Exceptions\BaseException;

class ManagerException extends BaseException
{
    public static function managerNotFound(): self
    {
        throw new self(
            ManagerExceptionsEnum::NOT_FOUND->value,
            404,
            ManagerExceptionsEnum::NOT_FOUND_CODE->value
        );
    }

    public static function notManager(): self
    {
        throw new self(
            ManagerExceptionsEnum::NOT_MANAGER->value,
            403,
            ManagerExceptionsEnum::NOT_MANAGER_CODE->value
        );
    }

    public static function unauthenticated(): self
    {
        throw new self(
            ManagerExceptionsEnum::UNAUTHENTICATED->value,
            401,
            ManagerExceptionsEnum::UNAUTHENTICATED_CODE->value
        );
    }
}
