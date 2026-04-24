<?php

namespace App\Enums\Insurance;

enum InsuranceExceptionsEnum: string
{
    case DUPLICATE_INSURANCE_CODE = 'A insurance with this code already exists';
    case DUPLICATE_INSURANCE_CODE_CODE = '422|1';
    case NOT_FOUND = 'Insurance not found';
    case NOT_FOUND_CODE = '404|1';
    case INVALID_PRICE = 'Insurance price must be greater than 0';
    case INVALID_PRICE_CODE = '422|2';
    case INVALID_DURATION = 'Insurance duration must be greater than 0';
    case INVALID_DURATION_CODE = '422|3';
}
