<?php

namespace App\Services;

class SessionService
{
    private const OTP_FLAG = 'authenticated_with_otp';
    private const PASSWORD_FLAG = 'authenticated_with_password';

    /**
     * Mark that the user has successfully completed the OTP step.
     */
    public static function markOtp(): void
    {
        session([self::OTP_FLAG => true]);
    }

    /**
     * Check if the OTP step has been completed.
     */
    public static function hasOtp(): bool
    {
        return session(self::OTP_FLAG, false);
    }

    /**
     * Mark that the user has successfully completed the password step.
     */
    public static function markPassword(): void
    {
        session([self::PASSWORD_FLAG => true]);
    }

    /**
     * Check if the password step has been completed.
     */
    public static function hasPassword(): bool
    {
        return session(self::PASSWORD_FLAG, false);
    }

    /**
     * Clear all authentication-related session flags (e.g. on logout).
     */
    public static function clearAuthFlags(): void
    {
        session()->forget([
            self::OTP_FLAG,
            self::PASSWORD_FLAG,
        ]);
    }
}
