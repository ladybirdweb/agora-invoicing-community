<?php

namespace App\Model\Payment;

use App\BaseModel;

class Tax extends BaseModel
{
    protected $table = 'taxes';
    protected $fillable = ['level', 'name', 'country', 'state', 'rate', 'active', 'tax_classes_id', 'compound'];

    //    public function taxClass()
//    {
//        return $this->belongsTo('App\Model\Payment\TaxClass');
//    }
}
