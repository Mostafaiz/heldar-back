<?php

namespace App\Http\Dto\Response;

use App\Utils\BaseWireableDto;
use \App\Models\Guarantee as GuaranteeModel;
use Carbon\Carbon;

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
        public Carbon $createdAt,
        public Carbon $updatedAt,
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
            createdAt: $guarantee->created_at,
            updatedAt: $guarantee->updated_at
        );
    }
}
