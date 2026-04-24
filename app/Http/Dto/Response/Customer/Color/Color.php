<?php

namespace App\Http\Dto\Response\Customer\Color;

use App\Models\Color as ColorModel;
use App\Utils\BaseWireableDto;

readonly class Color extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
    ) {}

    public static function from(ColorModel $color): Color
    {
        return new self(
            id: $color->id,
            name: $color->name,
            code: $color->code,
        );
    }
}
