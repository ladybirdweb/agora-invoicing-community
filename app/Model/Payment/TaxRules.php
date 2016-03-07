<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class TaxRules extends Model
{
    protected $table = 'tax_rules';
    protected $fillable = ['status', 'type', 'compound'];
}
