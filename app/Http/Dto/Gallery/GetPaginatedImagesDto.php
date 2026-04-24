<?php

namespace App\Http\Dto\Gallery;

class GetPaginatedImagesDto
{
    public function __construct(
        public int $page,
    ) {
    }
    static function makeDto(array $array): GetPaginatedImagesDto
    {
        return new self(
            page: $array['page'],
        );
    }
}