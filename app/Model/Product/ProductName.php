<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductName extends Model
{
    protected $table = 'product_names';
    protected $fillable = ['name'];

    public function taxes()
    {
        return $this->hasMany('App\Model\Payment\Tax', 'product_name_id');
    }

    public function plans()
    {
        return $this->hasMany('App\Model\Product\ProductPlan', 'product_name_id');
    }
}
