<?php

namespace App\Exceptions\Auth\SendOtp;

use Exception;

class OtpAlreadySentException extends Exception
{
    protected $message = "OTP already sent.";
    protected $code = 429;
}
