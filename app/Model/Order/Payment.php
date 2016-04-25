<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = ['parent_id', 'invoice_id', 'amount', 'payment_method', 'user_id', 'payment_status'];

    public function invoice()
    {
        return $this->belongsTo('App\Model\Order\Invoice');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
