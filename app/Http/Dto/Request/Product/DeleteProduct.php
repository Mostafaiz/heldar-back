<?php

namespace App\Http\Dto\Request\Product;

class DeleteProduct
{
    public function __construct(
        public int $id,
    ) {}

    public static function makeDto(array $array)
    {
        return new self(
            id: $array['id'],
        );
    }
}
