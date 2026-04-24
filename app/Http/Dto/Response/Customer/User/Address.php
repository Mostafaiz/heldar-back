<?php

namespace App\Http\Dto\Response\Customer\User;

use App\Models\Address as AddressModel;
use App\Utils\BaseWireableDto;

readonly class Address extends BaseWireableDto
{
    public function __construct(
        public int $id,
        public string $name,
        public int $provinceId,
        public int $cityId,
        public string $fullAddress,
        public int $zipcode,
    ) {}

    public static function from(AddressModel $address)
    {
        return new self(
            id: $address->id,
            name: $address->name,
            provinceId: $address->province_id,
            cityId: $address->city_id,
            fullAddress: $address->province->name . ' - ' . $address->city->name . ' - ' . $address->full_address . ' - کد پستی: ' . $address->zipcode,
            zipcode: $address->zipcode,
        );
    }
}
