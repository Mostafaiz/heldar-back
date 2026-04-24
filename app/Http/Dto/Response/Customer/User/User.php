<?php

namespace App\Http\Dto\Response\Customer\User;

use App\Models\User as UserModel;
use App\Utils\BaseWireableDto;

readonly class User extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public ?string $name,
        public ?string $family,
        public string $phone,
        public string $role,
    ) {}

    public static function from(UserModel $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            family: $user->family,
            phone: $user->username,
            role: $user->role->value,
        );
    }
}
