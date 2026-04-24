<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Monurakkaya\Lucg\Traits\HasUniqueCode;

class Transaction extends Model
{
    use HasUniqueCode;

    protected $fillable = [
        'user_id',
        'admin_id',
        'cart_id',
        'address',
        'amount',
        'method',
        'gateway',
        'authority',
        'ref_id',
        'status',
        'shipping_status',
        'cheque_image',
        'note',
        'response',
        'code',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    protected static function uniqueCodeType()
    {
        return 'numeric';
    }
}
