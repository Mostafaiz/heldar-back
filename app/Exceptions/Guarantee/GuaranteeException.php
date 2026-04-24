<?php

namespace App\Exceptions\Guarantee;

use App\Enums\Guarantee\GuaranteeExceptionsEnum;
use App\Exceptions\BaseException;
use App\Http\Dto\Response\Guarantee;

class GuaranteeException extends BaseException
{
    public static function duplicateGuaranteeSerial(): self
    {
        throw new self(
            GuaranteeExceptionsEnum::DUPLICATE_SERIAL->value,
            422,
            GuaranteeExceptionsEnum::DUPLICATE_SERIAL_CODE->value
        );
    }

    public static function guaranteeNotFound(): self
    {
        throw new self(
            GuaranteeExceptionsEnum::NOT_FOUND->value,
            404,
            GuaranteeExceptionsEnum::NOT_FOUND_CODE->value
        );
    }

    public static function guaranteeInvalidPrice(): self
    {
        throw new self(
            GuaranteeExceptionsEnum::INVALID_PRICE->value,
            422,
            GuaranteeExceptionsEnum::INVALID_PRICE_CODE->value
        );
    }

    public static function guaranteeInvalidDuration(): self
    {
        throw new self(
            GuaranteeExceptionsEnum::INVALID_DURATION->value,
            422,
            GuaranteeExceptionsEnum::INVALID_DURATION_CODE->value,
        );
    }
}
