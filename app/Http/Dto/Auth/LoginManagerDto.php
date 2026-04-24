<?php

namespace App\Http\Dto\Auth;

use App\Models\User;

class LoginManagerDto
{
    public function __construct(public string $phone, public string $password)
    {
    }
    public static function makeDto($array)
    {
        return new self(
            phone: $array['phone'],
            password: $array['password'],
        );
    }
}