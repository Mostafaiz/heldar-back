<?php

namespace App\Http\Dto\Attribute;

use App\Models\AttributeGroup;

class DeleteAttributeGroupDto
{

    public function __construct(public AttributeGroup $attributeGroup)
    {
    }

    public static function makeDto(AttributeGroup $attributeGroup)
    {
        return new static(attributeGroup: $attributeGroup);
    }
}