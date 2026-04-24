<?php

namespace App\Enums\Slide;

enum SlideExceptionsEnum: string
{
    case NOT_FOUND = 'Slide not found';
    case NOT_FOUND_CODE = '404|1';
}
