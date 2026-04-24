<?php

namespace App\Enums\Gallery;

enum FolderExceptionsEnum: string
{
    case DUPLICATE_NAME = 'A folder with this name already exists';
    case DUPLICATE_NAME_CODE = '422|1';
    case NOT_FOUND = 'Folder not found';
    case NOT_FOUND_CODE = '404|1';
}