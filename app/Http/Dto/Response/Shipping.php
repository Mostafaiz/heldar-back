<?php

namespace App\Http\Dto\Response;

use App\Models\Shipping as ShippingModel;
use App\Utils\BaseWireableDto;
use Carbon\Carbon;

readonly class Shipping extends BaseWireableDto
{

    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public int $price,
        public bool $status,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {}

    public static function from(ShippingModel $shipping)
    {
        return new self(
            id: $shipping->id,
            name: $shipping->name,
            description: $shipping->description,
            price: $shipping->price,
            status: $shipping->status,
            createdAt: $shipping->created_at,
            updatedAt: $shipping->updated_at,
        );
    }
}
