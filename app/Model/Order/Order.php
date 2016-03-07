<?php

namespace App\Model\Order;

use App\Model\Product\Subscription;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['client', 'order_status',
            'serial_key', 'product', 'domain', 'subscription', 'price_override', 'qty', 'invoice_id', ];
}
