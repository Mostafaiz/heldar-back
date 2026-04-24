<?php

namespace App\Services;

use App\Enums\UserRoleEnum;
use App\Exceptions\Manager\ManagerException;
use App\Http\Dto\Request\Manager\GetManager;
use App\Http\Dto\Request\Manager\UpdatePermissions as UpdatePermissionsDto;
use App\Http\Dto\Response\Permission as PermissionDto;
use App\Http\Dto\Response\User as UserDto;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;


class AccessControlService
{

    public function getOtherManagersWithPermissions(): Collection
    {
        $managers = User::where('role', UserRoleEnum::MANAGER)
            ->whereNotIn('id', [Auth::id()])
            ->with('permissions')
            ->get();

        return $managers;
    }

    public function getCurrentUserWithPermissions(): UserDto
    {
        $user = User::with('permissions')->find(Auth::id());

        if ($user === null)
            ManagerException::unauthenticated();


        return UserDto::from($user);
    }

    public function getAllPermissions(): array
    {
        return Permission::all()->except([6, 7, 8])
            ->map(fn($permission) => PermissionDto::from($permission))
            ->all();
    }

    public function getManagerPermissionsName(GetManager $dto): array
    {
        return User::find($dto->id)->permissions()->pluck('name')->toArray();
    }

    public function updateManagerPermissions(UpdatePermissionsDto $dto): array
    {
        $manager = User::find($dto->userId);

        if (!$manager)
            ManagerException::managerNotFound();

        $permissions = Permission::whereIn('name', $dto->permissions)->get();

        $manager->permissions()->sync($permissions->pluck('id'));

        return $manager->load('permissions')->pluck('name')->toArray();
    }
}
