<?php

namespace App\Enums\Shipping;

enum ShippingExceptionsEnum: string
{
    case SHIPPING_NOT_FOUND = 'Shipping not found';
    case SHIPPING_NOT_FOUND_CODE = '404|1';
    case DUPLICATE_NAME = 'A shipping with this name already exists';
    case DUPLICATE_NAME_CODE = '422|1';
}
