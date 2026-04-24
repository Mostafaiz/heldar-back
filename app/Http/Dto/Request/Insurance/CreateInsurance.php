<?php

namespace App\Http\Dto\Request\Insurance;

class CreateInsurance
{
    public function __construct(
        public string $name,
        public string $provider,
        public ?string $insuranceCode = null,
        public int $duration,
        public ?string $description = null,
        public int $price
    ) {
    }

    public static function makeDto($array)
    {
        return new self(
            name: $array['name'],
            provider: $array['provider'],
            insuranceCode: $array['insuranceCode'] ?? null,
            duration: $array['duration'],
            description: $array['description'] ?? null,
            price: $array['price']
        );
    }
}