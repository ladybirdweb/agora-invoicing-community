<?php

namespace App\Model\Product;

use App\BaseModel;

class ProductBundleRelation extends BaseModel
{
    protected $table = 'product_bundle_relations';
    protected $fillable = ['product_id', 'bundle_id'];
}
