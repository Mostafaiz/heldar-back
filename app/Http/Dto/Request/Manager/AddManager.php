<?php

namespace App\Http\Dto\Request\Manager;

class AddManager
{
    public function __construct(
        public int $username,
    ) {
    }

    public static function makeDto($array)
    {
        return new self(
            username: $array['phone'],
        );
    }
}