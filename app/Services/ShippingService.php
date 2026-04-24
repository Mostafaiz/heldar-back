<?php

namespace App\Services;

use App\Exceptions\Shipping\ShippingException;
use App\Http\Dto\Request\Shipping\DeleteShipping as DeleteShippingDto;
use App\Http\Dto\Request\Shipping\CreateShipping as CreateShippingDto;
use App\Http\Dto\Request\Shipping\UpdateShipping as UpdateShippingDto;
use App\Http\Dto\Response\Customer\Shipping\Shipping as CustomerShippingDto;
use App\Http\Dto\Response\Shipping as ShippingDto;
use App\Models\Shipping;

class ShippingService
{
    public function create(CreateShippingDto $dto)
    {
        return Shipping::create(
            [
                'name' => $dto->name,
                'description' => $dto->description,
                'price' => $dto->price,
                'status' => $dto->status,
            ]
        );
    }

    public function getAllShippings()
    {
        return Shipping::all()->map(function ($shipping) {
            return ShippingDto::from($shipping);
        })->toArray();
    }

    public function getAllShippingsCustomer()
    {
        return Shipping::get(['id', 'name', 'price', 'description'])->map(function ($value) {
            return CustomerShippingDto::from($value);
        })->toArray();
    }

    public function getShippingById(int $id)
    {
        $shipping = Shipping::find($id);
        if (!isset($shipping))
            ShippingException::shippingNotFound();

        return ShippingDto::from($shipping);
    }

    public function update(UpdateShippingDto $dto)
    {

        $shipping = Shipping::find($dto->id);
        if (!isset($shipping))
            ShippingException::shippingNotFound();

        $shipping->update(
            [
                'name' => $dto->name,
                'description' => $dto->description,
                'price' => $dto->price,
                'status' => $dto->status,
            ]
        );
    }

    public function delete(DeleteShippingDto $dto)
    {
        Shipping::findOrFail($dto->id)->delete();
    }
}
