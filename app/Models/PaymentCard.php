<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCard extends Model
{
    protected $fillable = [
        'owner_name',
        'bank_name',
        'card_number',
        'iban_number',
        'status',
        'color',
    ];
}
