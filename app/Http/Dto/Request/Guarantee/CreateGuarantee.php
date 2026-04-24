<?php

namespace App\Http\Dto\Request\Guarantee;

class CreateGuarantee
{
    public function __construct(
        public string $name,
        public string $provider,
        public ?string $serial = null,
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
            serial: $array['serial'] ?? null,
            duration: $array['duration'],
            description: $array['description'] ?? null,
            price: $array['price']
        );
    }
}