<?php

namespace App\Enums\Auth;

enum AuthExceptionsEnum: string
{
    case OTP_ALREADY_SENT = 'OTP already sent';
    case OTP_ALREADY_SENT_CODE = '429';
    case OTP_NOT_FOUND = 'OTP not found';
    case OTP_NOT_FOUND_CODE = '404|1';
    case OTP_EXPIRED = 'OTP code is expired';
    case OTP_EXPIRED_CODE = '410';
    case INVALID_OTP_CODE = 'Invalid OTP code';
    case INVALID_OTP_CODE_CODE = '422|1';
    case OTP_MISMATCH = 'Otp mismatch';
    case OTP_MISMATCH_CODE = '422';
    case TOO_MANY_OTP_REQUESTS = 'Too many OTP requests';
    case TOO_MANY_OTP_REQUESTS_CODE = '429|2';
    case INVALID_MANAGER_PASSWORD = 'Invalid manager password';
    case INVALID_MANAGER_PASSWORD_CODE = '422|3';
    case USER_NOT_MANAGER = 'User is not a manager';
    case USER_NOT_MANAGER_CODE = '403|1';
    case NOT_AUTHENTICATED = 'Not authenticated';
    case NOT_AUTHENTICATED_CODE = '401|1';
}
