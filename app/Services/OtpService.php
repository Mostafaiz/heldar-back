<?php
// app/Services/Auth/OtpService.php

namespace App\Services;

use App\Exceptions\Auth\AuthException;
use App\Http\Dto\Auth\SendOtpDto;
use App\Http\Dto\Auth\VerifyOtpDto;
use App\Models\Otp;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function __construct(
        private readonly RateLimiter $limiter,
        private readonly int $expireSeconds = 120
    ) {
    }

    public function sendOtp(SendOtpDto $dto): Otp
    {
        $key = "otp:send:{$dto->phone}";
        if ($this->limiter->tooManyAttempts($key, 5)) {
            throw AuthException::TooManyOtpAttempts();
        }
        $this->limiter->hit($key, $this->expireSeconds);

        if (
            Otp::where('phone', $dto->phone)
                ->where('expires_at', '>', now())
                ->exists()
        ) {
            throw AuthException::OtpAlreadySent();
        }

        $code = $this->makeCode();
        $SmsProviderService = new SmsProviderService();
        if (app()->environment('production')) {
            $SmsProviderService->sendOtp($dto->phone, $code);
        }
        $otp = Otp::create([
            'phone' => $dto->phone,
            'code' => Hash::make($code),
            'expires_at' => now()->addSeconds($this->expireSeconds),
        ]);

        Log::debug("[DEBUG] OTP to {$dto->phone}: {$code}");
        return $otp;
    }

    public function verifyOtp(VerifyOtpDto $dto): bool
    {
        $key = "otp:verify:{$dto->phone}";
        if ($this->limiter->tooManyAttempts($key, 5)) {
            throw AuthException::TooManyOtpAttempts();
        }
        $this->limiter->hit($key, $this->expireSeconds);

        $otp = Otp::where('phone', $dto->phone)
            ->latest()->first()
            ?? throw AuthException::OtpNotFound();

        if (now()->gt($otp->expires_at)) {
            $otp->delete();
            throw AuthException::OtpExpired();
        }

        if (!Hash::check($dto->code, $otp->code)) {
            throw AuthException::OtpMismatch();
        }

        $otp->delete();
        return true;
    }

    private function makeCode()
    {
        return app()->environment('local', 'testing')
            ? '123456'
            : str_pad(random_int(0, 999_999), 6, '0', STR_PAD_LEFT);
    }

    public function getActiveOtp(string $phone)
    {
        return Otp::where('phone', $phone)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }
}