<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class TaxOption extends BaseModel
{
    protected $table = 'tax_rules';
    protected $fillable = ['tax_enable', 'inclusive', 'shop_inclusive', 'cart_inclusive', 'rounding'];
}
