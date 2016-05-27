<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Price extends BaseModel
{
    protected $table = 'prices';
    protected $fillable = ['product_id', 'currency', 'subscription', 'price', 'sales_price'];
}
