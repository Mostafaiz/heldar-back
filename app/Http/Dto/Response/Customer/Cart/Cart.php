<?php

namespace App\Http\Dto\Response\Customer\Cart;

use App\Http\Dto\Response\Customer\Guarantee\Guarantee as GuaranteeDto;
use App\Http\Dto\Response\Customer\Insurance\Insurance as InsuranceDto;
use App\Http\Dto\Response\Customer\Pattern\Pattern as PatternDto;
use App\Http\Dto\Response\Customer\Size\Size as SizeDto;
use App\Models\CartItem as CartItemModel;
use App\Utils\BaseWireableDto;

readonly class Cart extends BaseWireableDto
{
    public function __construct(
        public int $index,
        public int $id,
        public string $name,
        public int $price,
        public int $discount,
        public PatternDto $pattern,
        public ?SizeDto $size,
        public ?GuaranteeDto $guarantee,
        public ?InsuranceDto $insurance,
        public int $quantity,
    ) {}

    public static function from(CartItemModel $cartItem): self
    {
        $variant = $cartItem->variant;

        return new self(
            index: $cartItem->id,
            id: $variant->product->id,
            name: $variant->product->name,
            price: $variant->price,
            discount: $variant->discount,
            pattern: PatternDto::from($variant->pattern),
            size: $variant->size ? SizeDto::from($variant->size) : null,
            guarantee: $cartItem->guarantee ? GuaranteeDto::from($cartItem->guarantee) : null,
            insurance: $cartItem->insurance ? InsuranceDto::from($cartItem->insurance) : null,
            quantity: $cartItem->quantity,
        );
    }
}
