<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class TaxClass extends BaseModel
{
    protected $table = 'tax_classes';
    protected $fillable = ['name'];
}
