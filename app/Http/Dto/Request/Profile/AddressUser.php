<?php

namespace App\Http\Dto\Request\Profile;

class AddressUser
{
    public function __construct(
        public int $provinceId,
        public int $cityId,
        public string $fullAddress,
        public int $zipCode,
    ) {}

    public static function makeDto(array $data): self
    {
        return new self(
            provinceId: $data['provinceId'],
            cityId: $data['cityId'],
            fullAddress: $data['fullAddress'],
            zipCode: $data['zipCode']

        );
    }
}
