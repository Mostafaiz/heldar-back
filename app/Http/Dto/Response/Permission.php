<?php

namespace App\Http\Dto\Response;

use App\Models\Permission as PermissionModel;
use App\Utils\BaseWireableDto;

readonly class Permission extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }

    public static function from(PermissionModel $permission): Permission
    {
        return new self(
            id: $permission->id,
            name: $permission->name
        );
    }
}
