<?php

namespace App\Http\Dto;

class CreateColorDto
{
    public function __construct(public string $name, public string $code)
    {
    }
    public static function makeDto($array)
    {
        return new self(
            name: $array["name"],
            code: $array["code"],
        );
    }
}