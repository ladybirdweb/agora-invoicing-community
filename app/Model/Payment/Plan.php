<?php

namespace App\Model\Payment;

use App\BaseModel;

class Plan extends BaseModel
{
    protected $table = 'plans';
    protected $fillable = ['name', 'product', 'allow_tax', 'days'];

    public function planPrice()
    {
        return $this->hasMany('App\Model\Payment\PlanPrice');
    }

    public function product()
    {
        return $this->hasMany('App\Model\Product\Product', 'id');
    }

    public function delete()
    {
        $this->planPrice()->delete();
        parent::delete();
    }
}
