<?php

namespace App\Services;

use App\Enums\PermissionEnum;
use App\Enums\UserRoleEnum;
use App\Exceptions\User\UserException;
use App\Http\Dto\Request\Manager\AddManager as AddManagerDto;
use App\Http\Dto\Response\User as UserDto;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerService
{
    public function addManager(AddManagerDto $dto)
    {
        $user = User::where('username', $dto->username)->first();

        if ($user === null)
            UserException::userNotFound();

        if ($user->role === UserRoleEnum::MANAGER)
            UserException::userAlreadyManager();

        $password = Hash::make('admin');

        $user->update([
            'role' => UserRoleEnum::MANAGER,
            'password' => $password,
        ]);

        $permissions = Permission::whereNot('name', PermissionEnum::MANAGE_USERS)->pluck('id')->toArray();
        $user->permissions()->sync($permissions);

        return UserDto::from($user);
    }

    public function removeManager(int $id)
    {
        User::where('id', '=', $id)->update(['role' => 'customer']);
    }
}
