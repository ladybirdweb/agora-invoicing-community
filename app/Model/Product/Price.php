<?php

namespace App\Model\Product;

use App\BaseModel;

class Price extends BaseModel
{
    protected $table = 'prices';
    protected $fillable = ['product_id', 'currency', 'subscription', 'price', 'sales_price'];
}
