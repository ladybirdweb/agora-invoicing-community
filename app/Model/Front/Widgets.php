<?php

namespace App\Model\Front;

use Illuminate\Database\Eloquent\Model;

class Widgets extends Model
{
    protected $table = 'widgets';
    protected $fillable = ['name','type','publish','content'];
}
