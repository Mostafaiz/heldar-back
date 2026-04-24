<?php

namespace App\Http\Dto\Request\Address;

class CreateAddress
{
    public function __construct(
        public string $name,
        public int $provinceId,
        public int $cityId,
        public string $zipcode,
        public string $fullAddress,
    ) {}

    public static function makeDto(array $array)
    {
        return new self(
            name: $array['name'],
            provinceId: $array['provinceId'],
            cityId: $array['cityId'],
            zipcode: $array['zipcode'],
            fullAddress: $array['fullAddress'],
        );
    }
}
