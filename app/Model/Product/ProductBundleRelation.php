<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class ProductBundleRelation extends Model
{
    protected $table = 'product_bundle_relations';
    protected $fillable = ['product_id','bundle_id'];
}
