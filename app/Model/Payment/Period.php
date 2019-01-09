<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $table = 'periods';
    protected $fillable = ['name', 'days'];

    public function plans()
    {
        return $this->hasMany('App\Model\Payment\Plan', 'plans_periods_relation')->withTimestamps();
    }
}
