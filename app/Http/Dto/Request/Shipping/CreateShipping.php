<?php

namespace App\Http\Dto\Request\Shipping;

use App\Livewire\Forms\CreateShippingForm;

class CreateShipping
{
    public function __construct(
        public string $name,
        public ?string $description,
        public string $price,
        public bool $status = true,
    ) {}

    public static function makeDto(CreateShippingForm $data): self
    {
        return new self(
            name: $data->name,
            description: $data->description,
            price: $data->price,
            status: $data->status,
        );
    }
}
