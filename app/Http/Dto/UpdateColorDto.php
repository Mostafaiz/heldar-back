<?php

namespace App\Http\Dto;

use App\Models\Color;

class UpdateColorDto
{
    public function __construct(public Color $color, public string $name, public string $code)
    {
    }

    public static function makeDto(Color $color, $array)
    {
        return new self(
            color: $color,
            name: $array["name"],
            code: $array["code"],
        );
    }

}