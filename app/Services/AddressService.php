<?php

namespace App\Services;

use App\Exceptions\Profile\AddressException;
use App\Http\Dto\Request\Address\CreateAddress as CreateAddressDto;
use App\Http\Dto\Request\Profile\UpdateAddressUser;
use App\Http\Dto\Response\Customer\User\Address as AddressDto;
use App\Models\Address;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;

class AddressService
{
    public function getProvinces()
    {
        $provinces = Province::get(['id', 'name']);
        return $provinces;
    }

    public function getCitiesByProvince(int $id)
    {
        $province = Province::where('id', $id)->first();

        if (!$province)
            AddressException::provinceNotFound();

        $cities = $province->cities()->get(['id', 'name']);

        return $cities;
    }

    public function create(CreateAddressDto $dto)
    {
        $user = Auth::user();

        $newAddress = $user->addresses()->create([
            'name' => $dto->name,
            'province_id' => $dto->provinceId,
            'city_id' => $dto->cityId,
            'full_address' => $dto->fullAddress,
            'zipcode' => $dto->zipcode,
        ]);

        return AddressDto::from($newAddress);
    }

    public function delete(int $id)
    {
        $address = Address::find($id);

        if (!isset($address))
            AddressException::addressNotFound();

        $address->delete();

        return AddressDto::from($address);
    }
    public function getAddressById(int $id)
    {
        $address = Address::find($id);
        if (!isset($address))
            AddressException::addressNotFound();
        return AddressDto::from($address);
    }

    public function update(UpdateAddressUser $dto)
    {

        $addressUser = Address::find($dto->id);

        if (!isset($addressUser))
            AddressException::addressNotFound();

        $addressUser->update(
            [
                'name' => $dto->name,
                'province_id' => $dto->provinceId,
                'city_id' => $dto->cityId,
                'full_address' => $dto->fullAddress,
                'zipcode' => $dto->zipCode,
            ]
        );

        return AddressDto::from($addressUser);
    }
}
