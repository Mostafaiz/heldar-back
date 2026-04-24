<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    protected $fillable = [
        'admin_phone',
        'admin_address',
        'first_sms_phone',
        'second_sms_phone',
        'about_us'
    ];
}
