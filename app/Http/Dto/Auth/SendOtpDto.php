<?php

namespace App\Http\Dto\Auth;

class SendOtpDto
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