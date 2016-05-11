<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class TaxClass extends Model
{
    protected $table = 'tax_classes';
    protected $fillable = ['name'];
}
