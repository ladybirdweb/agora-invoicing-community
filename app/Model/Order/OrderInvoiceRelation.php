<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceRelation extends Model
{
    protected $table = 'order_invoice_relations';
    protected $fillable = ['order_id', 'invoice_id'];
}
