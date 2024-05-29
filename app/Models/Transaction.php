<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'payment_type_id',
        'value',
    ];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
