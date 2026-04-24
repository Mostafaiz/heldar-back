<?php

namespace App\Http\Dto\Response;

use App\Models\User as UserModel;
use App\Utils\BaseWireableDto;
use Carbon\Carbon;

readonly class User extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $username,
        public ?string $name,
        public ?string $family,
        public ?string $email,
        public bool $status,
        public array $permissions,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {
    }

    public static function from(UserModel $user): User
    {
        return new self(
            id: $user->id,
            username: $user->username,
            name: $user->name,
            family: $user->family,
            email: $user->email,
            status: $user->status,
            permissions: $user->permissions()->pluck('name')->toArray(),
            createdAt: $user->created_at,
            updatedAt: $user->updated_at,
        );
    }
}