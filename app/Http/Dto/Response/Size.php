<?php

namespace App\Http\Dto\Response;

use App\Models\Size as SizeModel;
use App\Utils\BaseWireableDto;
use Carbon\Carbon;

readonly class Size extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {}

    public static function from(SizeModel $size)
    {
        return new self(
            id: $size->id,
            name: $size->name,
            createdAt: $size->created_at,
            updatedAt: $size->updated_at,
        );
    }
}
