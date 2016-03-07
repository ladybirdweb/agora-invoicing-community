<?php namespace Mmanos\Billing\Gateways\Local\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Coupon extends Model
{
	use SoftDeletingTrait;
	protected $connection = 'billinglocal';
	protected $guarded = array('id');
}
