<?php

namespace App\Model\Payment;

use App\BaseModel;

class Currency extends BaseModel
{
    protected $table = 'currencies';
    protected $fillable = ['code', 'symbol', 'name', 'base_conversion'];
}
