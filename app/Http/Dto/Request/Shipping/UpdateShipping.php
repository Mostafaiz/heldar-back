<?php

namespace App\Http\Dto\Request\Shipping;

use App\Livewire\Forms\UpdateShippingForm;

class UpdateShipping
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public string $price,
        public bool $status = true,
    ) {}

    public static function makeDto(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            description: $data['description'],
            price: $data['price'],
            status: $data['status'] ?? true,
        );
    }
}
