<?php

namespace App\Http\Dto\Attribute;

use App\Models\AttributeGroup;

class CreateAttributeDto
{
    public function __construct(
        public AttributeGroup $attributeGroup,
        public string $key
    ) {
    }
    public static function makeDto(AttributeGroup $attributeGroup, array $array)
    {
        return new self(
            attributeGroup: $attributeGroup,
            key: $array['key']
        );
    }
}