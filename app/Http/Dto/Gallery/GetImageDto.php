<?php

namespace App\Http\Dto\Gallery;

class GetImageDto
{
    public function __construct(public int $id)
    {
    }
    public static function makeDto(array $array): GetImageDto
    {
        return new self(
            id: $array['id'],
        );
    }
}