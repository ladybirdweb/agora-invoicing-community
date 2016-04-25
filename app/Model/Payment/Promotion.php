<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model {

    protected $table = 'promotions';
    protected $fillable = ['code', 'type', 'uses', 'value', 'start', 'expiry'];
   // protected $dates = ['start','expiry'];
    
    
    
    public function relation() {
        return $this->hasMany('App\Model\Payment\PromoProductRelation', 'promotion_id');
    }

    public function delete() {
        $this->relation()->delete();

        return parent::delete();
    }

}
