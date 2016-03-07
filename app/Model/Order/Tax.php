<?php

namespace App\Model\Order;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'taxes';
    protected $fillable = ['name', 'description', 'rate'];
}
