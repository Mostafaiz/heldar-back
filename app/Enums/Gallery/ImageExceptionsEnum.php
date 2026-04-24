<?php

namespace App\Enums\Gallery;

enum ImageExceptionsEnum: string
{
    case NOT_FOUND = 'Image not found';
    case NOT_FOUND_CODE = '404|1';
    case DESKTOP_IMAGE_NOT_FOUND = 'Desktop image not found';
    case DESKTOP_IMAGE_NOT_FOUND_CODE = '404|2';
    case MOBILE_IMAGE_NOT_FOUND = 'Mobile image not found';
    case MOBILE_IMAGE_NOT_FOUND_CODE = '404|3';
}
