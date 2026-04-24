<?php

namespace App\Http\Dto\Request\Profile;

class UpdateAddressUser
{
    public function __construct(
        public int $id,
        public string $name,
        public int $provinceId,
        public int $cityId,
        public string $fullAddress,
        public int $zipCode,
    ) {
    }

    public static function makeDto(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            provinceId: $data['provinceId'],
            cityId: $data['cityId'],
            fullAddress: $data['fullAddress'],
            zipCode: $data['zipcode']
        );
    }
}
