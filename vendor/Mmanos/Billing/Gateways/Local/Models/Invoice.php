<?php namespace Mmanos\Billing\Gateways\Local\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Invoice extends Model
{
	use SoftDeletingTrait;
	protected $connection = 'billinglocal';
	protected $guarded = array('id');
	protected $appends = array('subtotal', 'amount');
	protected $dates = array('period_started_at', 'period_ends_at');
	
	public function customer()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Customer')->withTrashed();
	}
	
	public function coupon()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Coupon')->withTrashed();
	}
	
	public function subscription()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Subscription')->withTrashed();
	}
	
	public function items()
	{
		return $this->hasMany('Mmanos\Billing\Gateways\Local\Models\Invoice\Item');
	}
	
	public function getSubtotalAttribute($value)
	{
		$subtotal = 0;
		foreach ($this->items as $item) {
			$subtotal += $item->amount;
		}
		
		return $subtotal;
	}
	
	public function getAmountAttribute($value)
	{
		$subtotal = $this->subtotal;
		$discount = 0;
		
		if ($this->coupon) {
			if ($this->coupon->percent_off) {
				$discount = $subtotal * ($this->coupon->percent_off / 100);
			}
			else if ($this->coupon->amount_off) {
				$discount = $subtotal - $this->coupon->amount_off;
			}
		}
		
		$amount = $subtotal - $discount;
		if ($amount < 0) {
			return 0;
		}
		
		return $amount;
	}
}
