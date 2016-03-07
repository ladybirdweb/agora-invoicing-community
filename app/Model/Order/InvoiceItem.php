<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';
    protected $fillable = ['invoice_id', 'product_name', 'regular_price', 'quantity', 'discount', 'tax_name', 'tax_percentage', 'tax_code', 'discount_mode', 'subtotal'];
}
