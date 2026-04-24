<?php

namespace App\Http\Dto\Request\Gallery;

class UpdateFolder
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }

    public static function makeDto(array $array): self
    {
        return new self(
            id: $array['id'],
            name: $array['name'],
        );
    }
}