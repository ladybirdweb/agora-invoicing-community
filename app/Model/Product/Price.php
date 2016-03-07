<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $table = 'prices';
    protected $fillable = ['product_id', 'currency', 'subscription', 'price', 'sales_price'];
}
