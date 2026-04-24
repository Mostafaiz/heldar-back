<?php

namespace App\Http\Dto\Attribute;

class CreateAttributeGroupDto
{

    public function __construct(
        public string $name,
        public array $attributes
    ) {
    }

    public static function makeDto(array $array)
    {
        return new self(
            name: $array['name'],
            attributes: $array['attributes']
        );
    }
}