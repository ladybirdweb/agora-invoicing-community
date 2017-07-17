<?php namespace Mmanos\Billing\Gateways\Local\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Card extends Model
{
	use SoftDeletingTrait;
	protected $connection = 'billinglocal';
	protected $guarded = array('id');
	
	public function customer()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Customer')->withTrashed();
	}
}
