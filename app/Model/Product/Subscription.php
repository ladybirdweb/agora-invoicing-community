<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';
    protected $fillable = ['name', 'description', 'days', 'ends_at', 'user_id', 'plan_id', 'order_id'];

//    public function order() {
//        return $this->hasMany('App\Model\Product\Order');
//    }

//    public function delete() {
//
//
//        $this->Plan()->delete();
//
//
//        return parent::delete();
//    }
}
