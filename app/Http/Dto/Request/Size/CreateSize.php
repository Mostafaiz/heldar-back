<?php

namespace App\Http\Dto\Request\Size;

class CreateSize
{
    public function __construct(
        public string $name,
    ) {}

    public static function makeDto(array $array)
    {
        return new self(
            name: $array['name'],
        );
    }
}
