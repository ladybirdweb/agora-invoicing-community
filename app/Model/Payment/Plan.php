<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;
use App\BaseModel;
class Plan extends BaseModel
{
    protected $table = 'plans';
    protected $fillable = ['name', 'description', 'days'];

    public function subscription()
    {
        return $this->hasMany('App\Model\Product\Subscription', 'plan_id');
    }

    public function delete()
    {
        $this->subscription()->delete();
        parent::delete();
    }
}
