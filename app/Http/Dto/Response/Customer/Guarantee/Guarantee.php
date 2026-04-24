<?php

namespace App\Http\Dto\Response\Customer\Guarantee;

use App\Models\Guarantee as GuaranteeModel;
use App\Utils\BaseWireableDto;

readonly class Guarantee extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $provider,
        public ?string $serial,
        public int $duration,
        public ?string $description,
        public int $price,

    ) {}

    public static function from(GuaranteeModel $guarantee): Guarantee
    {
        return new self(
            id: $guarantee->id,
            name: $guarantee->name,
            provider: $guarantee->provider,
            serial: $guarantee->serial,
            duration: $guarantee->duration,
            description: $guarantee->description,
            price: $guarantee->price,
        );
    }
}
