<?php

namespace App\Exceptions\Insurance;

use App\Enums\Insurance\InsuranceExceptionsEnum;
use App\Exceptions\BaseException;

class InsuranceException extends BaseException
{
    public static function duplicateInsuranceCode(): self
    {
        throw new self(
            InsuranceExceptionsEnum::DUPLICATE_INSURANCE_CODE->value,
            422,
            InsuranceExceptionsEnum::DUPLICATE_INSURANCE_CODE_CODE->value
        );
    }

    public static function insuranceNotFound(): self
    {
        throw new self(
            InsuranceExceptionsEnum::NOT_FOUND->value,
            404,
            InsuranceExceptionsEnum::NOT_FOUND_CODE->value
        );
    }

    public static function insuranceInvalidPrice(): self
    {
        throw new self(
            InsuranceExceptionsEnum::INVALID_PRICE->value,
            422,
            InsuranceExceptionsEnum::INVALID_PRICE_CODE->value
        );
    }

    public static function insuranceInvalidDuration(): self
    {
        throw new self(
            InsuranceExceptionsEnum::INVALID_DURATION->value,
            422,
            InsuranceExceptionsEnum::INVALID_DURATION_CODE->value,
        );
    }
}
