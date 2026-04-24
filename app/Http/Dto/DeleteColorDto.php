<?php

namespace App\Http\Dto;

use App\Models\Color;

class DeleteColorDto
{
    public function __construct(public Color $color)
    {
    }
    public function toArray()
    {
        return [];
    }

    public static function makeDto(Color $color)
    {
        return new self(
            color: $color,
        );
    }

}