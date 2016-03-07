<?php namespace Mmanos\Billing\Gateways\Local\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Charge extends Model
{
	use SoftDeletingTrait;
	protected $guarded = array('id');
	protected $connection = 'billinglocal';
	
	public function customer()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Customer')->withTrashed();
	}
	
	public function card()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Card')->withTrashed();
	}
	
	public function capture()
	{
		$this->captured = 1;
		$this->paid = 1;
		$this->refunded = 0;
		$this->save();
	}
	
	public function refund()
	{
		if (!$this->paid) {
			return;
		}
		
		$this->refunded = 1;
		$this->save();
	}
}
