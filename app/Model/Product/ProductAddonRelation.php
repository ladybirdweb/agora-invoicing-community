<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class ProductAddonRelation extends BaseModel
{
    protected $table = 'product_addon_relations';
    protected $fillable = ['addon_id', 'product_id'];
}
