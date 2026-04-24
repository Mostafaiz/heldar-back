<?php

namespace App\Http\Dto\Response\Customer\Product;

use App\Http\Dto\Response\Customer\Image as ImageDto;
use App\Utils\BaseWireableDto;

readonly class ProductCard extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?ImageDto $image,
        public ?int $price,
        public ?int $quantity,
        public ?int $discount,
    ) {}

    public static function from($Product, $firstVariant, $firstImage)
    {
        return new self(
            id: $Product->id,
            name: $Product->name,
            image: ImageDto::from($firstImage) ?? null,
            price: $firstVariant?->price,
            quantity: $firstVariant->quantity,
            discount: $firstVariant?->discount,
        );
    }
}
