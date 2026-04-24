<?php

namespace App\Enums\User;

enum UserExceptionsEnum: string
{
    case NOT_FOUND = 'User not found';
    case NOT_FOUND_CODE = '404|1';
    case NOT_USER = 'This user is not a user';
    case NOT_USER_CODE = '403|1';
    case ALREADY_MANAGER = 'User is already a manager';
    case ALREADY_MANAGER_CODE = '400|1';

}
