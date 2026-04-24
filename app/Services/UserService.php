<?php

namespace App\Services;

use App\Exceptions\User\UserException;
use App\Http\Dto\Response\Customer\User\Address as AddressDto;
use App\Models\User;

class UserService
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUsersByNameOrUsername(string $search = '')
    {

        if ($search) {
            $query = User::query();

            $searchParts = explode(' ', $search);

            $query->where(function ($q) use ($searchParts) {
                foreach ($searchParts as $part) {
                    if (trim($part) !== '') {
                        $q->where('name', 'like', "%{$part}%");
                        $q->orWhere('family', 'like', "%{$part}%");
                        $q->orWhere('username', 'like', "%{$part}%");
                    }
                }
            });

            return $query->get();
        }

        return User::all();
    }

    public function getUserById(int $id)
    {
        return User::find($id);
    }

    public function getAllUsersWithAddresses()
    {
        return User::with('addresses:id,name')->get(['id', 'name', 'family', 'username', 'role', 'level']);
    }

    public function getAllUsersWithAddressesBySearch(?string $search = null)
    {
        $users = User::query();

        if ($search) {
            $words = preg_split('/\s+/', trim($search));

            $users->where(function ($query) use ($words) {
                foreach ($words as $word) {
                    $query->where(function ($q) use ($word) {
                        $q->where('name', 'like', "%{$word}%")
                            ->orWhere('family', 'like', "%{$word}%")
                            ->orWhere('username', 'like', "%{$word}%");
                    });
                }
            });
        }

        return $users
            ->with('addresses:id,user_id,name')
            ->get(['id', 'name', 'family', 'username', 'role', 'level']);
    }

    public function getCurrentUserAddresses()
    {
        return auth()->user()?->addresses()->with(['province', 'city'])->get(['id', 'name', 'province_id', 'city_id', 'zipcode', 'full_address'])->map(function ($value) {
            return AddressDto::from($value);
        })->toArray() ?? [];
    }

    public function getCurrentUser()
    {
        return auth()->user();
    }

    public function getUserLevelById(int $userId)
    {
        $user = User::find($userId);

        if (!isset($user))
            UserException::userNotFound();

        return $user->level;
    }

    public function getCurrentUserLevel()
    {
        return auth()?->user()?->level ?? null;
    }

    public function updateUserLevel(int $userId, string $level)
    {
        $user = User::find($userId);

        if (!isset($user))
            UserException::userNotFound();

        $user->level = $level;
        $user->save();
    }

    public function isCurrentUserManager(): bool
    {
        return auth()->user()->isManager();
    }

    public function isLoggedIn(): bool
    {
        return auth()->user() != null;
    }
}
