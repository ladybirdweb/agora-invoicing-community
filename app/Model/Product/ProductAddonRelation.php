<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductAddonRelation extends Model
{
   protected $table = 'product_addon_relations';
   protected $fillable = ['addon_id','product_id'];
}
