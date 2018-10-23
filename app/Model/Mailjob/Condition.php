<?php

namespace App\Model\Mailjob;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
	protected $table = 'conditions';
    protected $fillable =['jobs','value'];

}
