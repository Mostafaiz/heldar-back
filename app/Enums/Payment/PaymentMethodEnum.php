<?php

namespace App\Enums\Payment;

enum PaymentMethodEnum: string
{
    case GATEWAY = 'gateway';
    case CHEQUE  = 'cheque';
    case MANAGER = 'manager';
}
