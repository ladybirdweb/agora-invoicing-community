<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
    protected $table = 'product_pricings';
    protected $fillable = ['currency', 'new_pricing', 'renew_pricing', 'duration', 'product_plan_id', 'created_at','updated_at'];

   public function productName()
   {
   	return $this->belongsTo('App\Model\Product\ProductPlan');
   }
}
