<?php

namespace App\Services;

use App\Exceptions\User\UserException;
use App\Http\Dto\Request\Profile\UpdateUserInfo;
use \App\Http\Dto\Response\Customer\User\User as UserDto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    public function updateUserInfo(UpdateUserInfo $dto)
    {
        $user = Auth::user();

        if (!$user)
            UserException::userNotFound();

        $user->update([
            "name" => $dto->name,
            "family" => $dto->family
        ]);

        return $user;
    }

    public function getUserInfo()
    {
        $user = Auth::user();

        if (!$user)
            UserException::userNotFound();

        return UserDto::from($user);
    }
}
