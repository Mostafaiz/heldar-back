<?php

namespace App\Enums\Guarantee;

enum GuaranteeExceptionsEnum: string
{
    case DUPLICATE_SERIAL = 'A guarantee with this serial already exists';
    case DUPLICATE_SERIAL_CODE = '422|1';
    case NOT_FOUND = 'guarantee not found';
    case NOT_FOUND_CODE = '404|1';
    case INVALID_PRICE = 'guarantee price must be greater than 0';
    case INVALID_PRICE_CODE = '422|2';
    case INVALID_DURATION = 'guarantee duration must be greater than 0';
    case INVALID_DURATION_CODE = '422|3';
}
