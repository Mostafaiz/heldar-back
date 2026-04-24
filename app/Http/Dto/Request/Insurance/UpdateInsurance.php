<?php

namespace App\Http\Dto\Request\Insurance;

class UpdateInsurance
{
    public function __construct(
        public int $id,
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
            id: $array['id'],
            name: $array['name'],
            provider: $array['provider'],
            insuranceCode: $array['insuranceCode'] ?? null,
            duration: $array['duration'],
            description: $array['description'] ?? null,
            price: $array['price']
        );
    }
}