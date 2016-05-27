<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class PromoProductRelation extends BaseModel
{
    protected $table = 'promo_product_relations';
    protected $fillable = ['product_id', 'promotion_id'];
}
