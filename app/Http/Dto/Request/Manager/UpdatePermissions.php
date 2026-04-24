<?php

namespace App\Http\Dto\Request\Manager;

class UpdatePermissions
{
    public function __construct(
        public int $userId,
        public array $permissions,
    ) {
    }

    public static function makeDto(int $userId, array $permissions): self
    {
        return new self(
            userId: $userId,
            permissions: $permissions
        );
    }
}