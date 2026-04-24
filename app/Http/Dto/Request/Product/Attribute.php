<?php

namespace App\Http\Dto\Request\Product;

class Attribute
{
    public function __construct(
        public array $items,
    ) {
    }

    public static function makeDto(array $data): self
    {
        return new self(
            items: $data['items'] ?? [],
        );
    }
}
