<?php

namespace App\Http\Dto\Request\Customer\Cart;

class UpdateCartItem
{
    public function __construct(
        public int $productId,
        public int $patternId,
        public ?int $sizeId,
        public ?int $guaranteeId,
        public ?int $insuranceId,
        public int $quantity
    ) {}

    public static function makeDto(array $array): self
    {
        return new self(
            productId: $array['productId'],
            patternId: $array['patternId'],
            sizeId: $array['sizeId'] ?? null,
            guaranteeId: $array['guaranteeId'] ?? null,
            insuranceId: $array['insuranceId'] ?? null,
            quantity: $array['quantity'],
        );
    }
}
