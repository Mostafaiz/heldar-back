<?php

namespace App\Exceptions\Auth\VerifyOtp;

use Exception;

class OtpNotFoundException extends Exception
{
    protected $message = 'OTP not found.';
    protected $code = 404;
}
