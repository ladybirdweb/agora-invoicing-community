<?php

namespace App\Model\Order;

use App\BaseModel;

class Payment extends BaseModel
{
    protected $table = 'payments';

    protected $fillable = ['parent_id', 'invoice_id', 'amount',
        'payment_method', 'user_id', 'payment_status', 'created_at', 'amt_to_credit', ];

    public function invoice()
    {
        return $this->belongsTo(\App\Model\Order\Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    //    public function setCreatedAtAttribute($value) {
//        dd($value);
//    }
}
