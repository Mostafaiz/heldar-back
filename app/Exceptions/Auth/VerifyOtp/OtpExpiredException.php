<?php

namespace App\Exceptions\Auth\VerifyOtp;

use Exception;

class OtpExpiredException extends Exception
{
    protected $message = "OTP code is expired.";
    protected $code = 410;
}
