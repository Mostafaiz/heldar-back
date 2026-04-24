<?php

namespace App\Services;

use App\Exceptions\Auth\AuthException;
use App\Http\Dto\Auth\LoginDto;
use App\Http\Dto\Auth\LoginManagerDto;
use App\Http\Dto\Auth\UserFirstOrCreateDto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticationService
{
    public function userFirstOrCreate(UserFirstOrCreateDto $dto): User
    {
        return User::firstOrCreate(
            ['username' => $dto->phone],
            ['name' => $dto->name ?? null, 'role' => $dto->role ?? null]
        );
    }

    public function loginWithOtp(UserFirstOrCreateDto $userDto, LoginDto $loginDto): void
    {
        $user = User::firstOrCreate(
            ['username' => $userDto->phone],
        );

        Auth::login($user, $loginDto->remember);
        session()->regenerate();
        SessionService::markOtp();

        Log::info("User logged in via OTP: {$user->username}");
    }

    public function verifyManagerPassword(LoginManagerDto $dto): void
    {
        $user = Auth::user() ?: throw AuthException::NotAuthenticated();

        if ($user->username !== $dto->phone) {
            throw AuthException::UserNotManager();
        }

        if (!Hash::check($dto->password, $user->getAuthPassword())) {
            throw AuthException::InvalidManagerPassword();
        }

        session()->regenerate();
        SessionService::markPassword();

        Log::info("Manager authenticated: {$user->username}");
    }

    public function logout(): void
    {
        if(!Auth::check()) {
            return;
        }

        Auth::logout();
        SessionService::clearAuthFlags();
        session()->invalidate();
        session()->regenerateToken();

        Log::info("User logged out");
    }
    public function isAuthenticated(): bool
    {
        return Auth::check();
    }


    public function isManagerAuthenticated(): bool
    {
        return Auth::check()
            && Auth::user()->isManager()
            && SessionService::hasPassword();
    }
}
