<?php

namespace App\Http\Dto\Response\Customer\Shipping;

use App\Models\Shipping as ShippingModel;
use App\Utils\BaseWireableDto;

readonly class Shipping extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $price,
        public ?string $description,
    ) {}

    public static function from(ShippingModel $size)
    {
        return new self(
            id: $size->id,
            name: $size->name,
            price: $size->price,
            description: $size->description,
        );
    }
}
