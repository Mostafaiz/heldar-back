<?php

namespace App\Enums\Size;

enum SizeExceptionsEnum: string
{
    case DUPLICATE_NAME = 'A size with this name already exists';
    case DUPLICATE_NAME_CODE = '422|1';
    case NOT_FOUND = 'Size not found';
    case NOT_FOUND_CODE = '404|1';
}
