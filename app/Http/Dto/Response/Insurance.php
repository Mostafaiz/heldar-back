<?php

namespace App\Http\Dto\Response;

use App\Utils\BaseWireableDto;
use App\Models\Insurance as InsuranceModel;
use Carbon\Carbon;

readonly class Insurance extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $provider,
        public ?string $insuranceCode,
        public int $duration,
        public ?string $description,
        public int $price,
        public Carbon $createdAt,
        public Carbon $updatedAt,
    ) {
    }

    public static function from(InsuranceModel $insurance): Insurance
    {
        return new self(
            id: $insurance->id,
            name: $insurance->name,
            provider: $insurance->provider,
            insuranceCode: $insurance->insurance_code,
            duration: $insurance->duration,
            description: $insurance->description,
            price: $insurance->price,
            createdAt: $insurance->created_at,
            updatedAt: $insurance->updated_at
        );
    }
}
