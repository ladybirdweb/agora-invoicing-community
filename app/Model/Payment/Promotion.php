<?php

namespace App\Model\Payment;

use App\BaseModel;

class Promotion extends BaseModel
{
    protected $table = 'promotions';
    protected $fillable = ['code', 'type', 'uses', 'value', 'start', 'expiry'];
    // protected $dates = ['start','expiry'];

    public function relation()
    {
        return $this->hasMany('App\Model\Payment\PromoProductRelation', 'promotion_id');
    }

    public function delete()
    {
        $this->relation()->delete();

        return parent::delete();
    }
}
