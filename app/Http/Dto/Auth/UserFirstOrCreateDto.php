<?php

namespace App\Http\Dto\Auth;

class UserFirstOrCreateDto
{
    public function __construct(public string $phone)
    {
    }
    public static function makeDto($array)
    {
        return new self(
            phone: $array["phone"],
        );
    }
}