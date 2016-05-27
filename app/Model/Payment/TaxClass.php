<?php

namespace App\Model\Payment;

use App\BaseModel;

class TaxClass extends BaseModel
{
    protected $table = 'tax_classes';
    protected $fillable = ['name'];
}
