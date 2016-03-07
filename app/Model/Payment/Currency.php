<?php namespace App\Model\Payment;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model {

	protected $table = 'currencies';
        protected $fillable = ['code','symbol','name','base_conversion'];

}
