<?php

namespace App\Exceptions\Auth;

use App\Enums\Auth\AuthExceptionsEnum;
use App\Exceptions\BaseException;

class AuthException extends BaseException
{
    public static function OtpAlreadySent(): self
    {
        throw new self(
            AuthExceptionsEnum::OTP_ALREADY_SENT->value,
            429,
            AuthExceptionsEnum::OTP_ALREADY_SENT_CODE->value
        );
    }

    public static function OtpNotFound(): self
    {
        throw new self(
            AuthExceptionsEnum::OTP_NOT_FOUND->value,
            404,
            AuthExceptionsEnum::OTP_NOT_FOUND_CODE->value
        );
    }

    public static function OtpExpired(): self
    {
        throw new self(
            AuthExceptionsEnum::OTP_EXPIRED->value,
            410,
            AuthExceptionsEnum::OTP_EXPIRED_CODE->value
        );
    }

    public static function OtpMismatch(): self
    {
        throw new self(
            AuthExceptionsEnum::OTP_MISMATCH->value,
            422,
            AuthExceptionsEnum::OTP_MISMATCH_CODE->value
        );
    }

    public static function TooManyOtpAttempts(): self
    {
        throw new self(
            AuthExceptionsEnum::TOO_MANY_OTP_REQUESTS->value,
            429,
            AuthExceptionsEnum::TOO_MANY_OTP_REQUESTS_CODE->value
        );
    }

    public static function InvalidManagerPassword(): self
    {
        throw new self(
            AuthExceptionsEnum::INVALID_MANAGER_PASSWORD->value,
            422,
            AuthExceptionsEnum::INVALID_MANAGER_PASSWORD_CODE->value
        );
    }

    public static function UserNotManager(): self
    {
        throw new self(
            AuthExceptionsEnum::USER_NOT_MANAGER->value,
            403,
            AuthExceptionsEnum::USER_NOT_MANAGER_CODE->value
        );
    }

    public static function NotAuthenticated(): self
    {
        throw new self(
            AuthExceptionsEnum::NOT_AUTHENTICATED->value,
            401,
            AuthExceptionsEnum::NOT_AUTHENTICATED_CODE->value
        );
    }
}
