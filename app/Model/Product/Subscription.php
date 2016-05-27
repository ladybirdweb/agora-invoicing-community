<?php

namespace App\Model\Product;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Subscription extends Model
{
    protected $table = 'subscriptions';
    protected $fillable = ['name', 'description', 'days', 'ends_at', 'user_id', 'plan_id', 'order_id', 'deny_after_subscription'];

    public function plan()
    {
        return $this->belongsTo('App\Model\Payment\Plan');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

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
