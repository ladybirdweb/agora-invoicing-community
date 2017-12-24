<?php

namespace App\Model\Payment;

use App\BaseModel;

class Tax extends BaseModel
{
    protected $table = 'taxes';
    protected $fillable = ['product_name_id', 'tax_class_name', 'country', 'state', 'rate', 'start_date', 'end_date','time_zone'];

   public function productName()
   {
   return $this ->belongsTo('App\Model\Product\ProductName');
   }
   

}
