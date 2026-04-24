<?php

namespace App\Http\Dto\Request\Gallery;

class GetImage
{
    public function __construct(public int $id) {}
    public static function makeDto(array $array): self
    {
        return new self(
            id: $array['id'],
        );
    }
}
