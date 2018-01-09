<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductPlan extends Model
{
   public function productName()
   {
   	return $this->belongsTo('App\Model\Product\ProductName');
   }

     public function productPricing()
    {
        return $this->hasMany('App\Model\Product\ProductPricing','product_plan_id');
    }
}
