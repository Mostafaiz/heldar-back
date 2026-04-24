<?php

namespace App\Exceptions\Profile;

use App\Enums\Profile\AddressExceptionsEnum;
use App\Exceptions\BaseException;

class AddressException extends BaseException
{
    public static function addressNotFound(): self
    {
        throw new self(
            AddressExceptionsEnum::ADDRESS_NOT_FOUND->value,
            404,
            AddressExceptionsEnum::ADDRESS_NOT_FOUND_CODE->value
        );
    }

    public static function provinceNotFound(): self
    {
        throw new self(
            AddressExceptionsEnum::PROVINCE_NOT_FOUND->value,
            404,
            AddressExceptionsEnum::PROVINCE_NOT_FOUND_CODE->value
        );
    }
}
