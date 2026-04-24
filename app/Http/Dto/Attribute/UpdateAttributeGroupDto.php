<?php

namespace App\Http\Dto\Attribute;

use App\Models\AttributeGroup;

class UpdateAttributeGroupDto
{

    public function __construct(
        public AttributeGroup $attributeGroup,
        public string $name
    ) {
    }
    public static function makeDto(AttributeGroup $attributeGroup, $array)
    {
        return new static(
            attributeGroup: $attributeGroup,
            name: $array['name']
        );
    }
}