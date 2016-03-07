<?php

namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';
    protected $fillable = ['level', 'name', 'country', 'state', 'rate'];
}
