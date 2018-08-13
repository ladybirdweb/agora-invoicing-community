<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
   protected $table = 'product_categories';
   protected $fillable = ['id','category_name'];
}
