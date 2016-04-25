<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';
    protected $fillable = ['level', 'name', 'country', 'state', 'rate', 'active', 'tax_classes_id', 'compound'];

    public function taxClass()
    {
        return $this->belongsTo('App\Model\Payment\TaxClass');
    }
}
