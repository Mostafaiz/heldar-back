<?php

namespace App\Http\Dto\Request\Profile;

class UpdateUserInfo
{
    public function __construct(
        public string $name,
        public string $family
    ) {}

    public static function makeDto(array $data): self
    {
        return new self(
            name: $data['name'],
            family: $data['family'],
        );
    }
}