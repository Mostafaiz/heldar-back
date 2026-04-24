<?php

namespace App\Http\Dto\Response;

use App\Models\User;
use App\Utils\BaseWireableDto;

readonly class UserInfo extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $username,
        public ?string $name,
        public ?string $family,
        public ?string $email,
        public bool $status,
        public ?string $address,

    ) {}

    public static function from(User $user): self
    {
        return new self(
            id: $user->id,
            username: $user->username,
            name: $user->name,
            family: $user->family,
            email: $user->email,
            status: (bool) $user->status,
            address: $user->address,
        );
    }
}
