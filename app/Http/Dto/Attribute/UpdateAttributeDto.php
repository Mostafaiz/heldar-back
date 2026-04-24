<?php

namespace App\Http\Dto\Attribute;

use App\Models\Attribute;

class UpdateAttributeDto
{

    public function __construct(
        public Attribute $attribute,
        public string $key
    ) {
    }
    public static function makeDto(Attribute $attribute, $array)
    {
        return new self(
            attribute: $attribute,
            key: $array['key']
        );
    }
}