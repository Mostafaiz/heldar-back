<?php

namespace App\Http\Dto\Auth;

class VerifyOtpDto
{
    public function __construct(public string $phone, public string $code)
    {
    }
    public static function makeDto($array)
    {
        return new self(
            phone: $array["phone"],
            code: $array["code"],
        );
    }
}