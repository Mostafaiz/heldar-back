<?php

namespace App\Enums\Manager;

enum ManagerExceptionsEnum: string
{
    case NOT_FOUND = 'Manager not found';
    case NOT_FOUND_CODE = '404|1';
    case NOT_MANAGER = 'This user is not a manager';
    case NOT_MANAGER_CODE = '403|1';
    case UNAUTHENTICATED = 'Authentication required.';
    case UNAUTHENTICATED_CODE = '401|1';
}
