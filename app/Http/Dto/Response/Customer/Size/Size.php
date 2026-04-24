<?php

namespace App\Http\Dto\Response\Customer\Size;

use App\Models\Size as SizeModel;
use App\Utils\BaseWireableDto;

readonly class Size extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}

    public static function from(SizeModel $size)
    {
        return new self(
            id: $size->id,
            name: $size->name,
        );
    }
}
