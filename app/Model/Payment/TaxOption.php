<?php

namespace App\Model\Payment;

use App\BaseModel;

class TaxOption extends BaseModel
{
    protected $table = 'tax_rules';
    protected $fillable = ['tax_enable', 'inclusive', 'shop_inclusive', 'cart_inclusive', 'rounding'];
}
