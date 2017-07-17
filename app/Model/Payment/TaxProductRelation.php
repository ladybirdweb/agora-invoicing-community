<?php

namespace App\Model\Payment;

use App\BaseModel;

class TaxProductRelation extends BaseModel
{
    protected $table = 'tax_product_relations';
    protected $fillable = ['product_id', 'tax_class_id'];

    public function tax()
    {
        return $this->belongsTo('App\Model\Payment\TaxClass', 'tax_class_id');
    }

    public function product()
    {
        return $this->hasMany('App\Model\Product\Product', 'product_id');
    }
}
