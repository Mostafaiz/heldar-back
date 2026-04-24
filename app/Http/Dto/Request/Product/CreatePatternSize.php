<?php

namespace App\Http\Dto\Request\Product;

class CreatePatternSize
{
    public function __construct(
        public ?int $id,
        public ?string $name,
        public float $price,
        public ?float $discount,
        public int $quantity,
        public ?string $sku
    ) {}

    public static function makeDto(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'] ?? null,
            price: (float) $data['price'],
            discount: isset($data['discount']) ? (float) $data['discount'] : null,
            quantity: (int) $data['quantity'],
            sku: $data['sku'] ?? null,
        );
    }
}