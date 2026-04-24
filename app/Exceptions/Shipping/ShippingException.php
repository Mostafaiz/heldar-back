<?php

namespace App\Exceptions\Shipping;

use App\Enums\Shipping\ShippingExceptionsEnum;
use App\Exceptions\BaseException;

class ShippingException extends BaseException
{
    public static function shippingNotFound(): self
    {
        throw new self(
            ShippingExceptionsEnum::SHIPPING_NOT_FOUND->value,
            404,
            ShippingExceptionsEnum::SHIPPING_NOT_FOUND_CODE->value
        );
    }

    public static function duplicateName(): self
    {
        throw new self(
            ShippingExceptionsEnum::DUPLICATE_NAME->value,
            409,
            ShippingExceptionsEnum::DUPLICATE_NAME_CODE->value
        );
    }
}
