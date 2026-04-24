<?php

namespace App\Http\Dto\Request\Guarantee;

class DeleteGuarantee
{
    public function __construct(
        public int $id
    ) {}
    public static function makeDto($array)
    {
        return new self(
            id: $array['id'],
        );
    }
}
