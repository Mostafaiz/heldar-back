<?php

namespace App\Http\Dto\Auth;

use App\Models\User;

class LoginDto
{
    public function __construct(public User $user, public bool $remember)
    {
    }
    public static function makeDto(User $user, $array)
    {
        return new self(
            user: $user,
            remember: $array["remember"],
        );
    }
}