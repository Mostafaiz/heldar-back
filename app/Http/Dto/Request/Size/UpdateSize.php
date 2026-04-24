<?php

namespace App\Http\Dto\Request\Size;

class UpdateSize
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }

    public static function makeDto($array)
    {
        return new self(
            id: $array['id'],
            name: $array['name'],
        );
    }
}