<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    protected $table = 'plan_prices';
    protected $fillable = ['plan_id', 'currency', 'add_price', 'renew_price'];
}
