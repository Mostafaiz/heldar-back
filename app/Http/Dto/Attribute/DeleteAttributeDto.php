<?php

namespace App\Http\Dto\Attribute;

use App\Models\Attribute;


class DeleteAttributeDto
{

    public function __construct(public Attribute $attribute)
    {
    }

    public static function makeDto(Attribute $attribute)
    {
        return new static(attribute: $attribute);
    }
}