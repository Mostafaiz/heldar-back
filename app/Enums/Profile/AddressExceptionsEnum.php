<?php

namespace App\Enums\Profile;

enum AddressExceptionsEnum: string
{
    case ADDRESS_NOT_FOUND = 'Address not found';
    case ADDRESS_NOT_FOUND_CODE = '404|1';
    case PROVINCE_NOT_FOUND = 'Province not found';
    case PROVINCE_NOT_FOUND_CODE = '404|2';
}