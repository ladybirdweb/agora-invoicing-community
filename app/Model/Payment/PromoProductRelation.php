<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class PromoProductRelation extends Model
{
    protected $table = 'promo_product_relations';
    protected $fillable = ['product_id','promotion_id'];
}
