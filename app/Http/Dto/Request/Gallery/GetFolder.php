<?php

namespace App\Http\Dto\Request\Gallery;

class GetFolder
{
    public function __construct(public int $id)
    {
    }
    public static function makeDto(array $array): self
    {
        return new self(
            id: $array['id'],
        );
    }
}