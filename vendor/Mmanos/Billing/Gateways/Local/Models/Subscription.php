<?php namespace Mmanos\Billing\Gateways\Local\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Carbon\Carbon;

class Subscription extends Model
{
	use SoftDeletingTrait;
	protected $connection = 'billinglocal';
	protected $guarded = array('id');
	protected $appends = array('period_started_at', 'period_ends_at');
	protected $dates = array('period_started_at', 'period_ends_at', 'trial_ends_at', 'cancel_at');
	
	public function customer()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Customer')->withTrashed();
	}
	
	public function plan()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Plan')->withTrashed();
	}
	
	public function card()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Card');
	}
	
	public function coupon()
	{
		return $this->belongsTo('Mmanos\Billing\Gateways\Local\Models\Coupon')->withTrashed();
	}
	
	public function getPeriodStartedAtAttribute($value)
	{
		if ($this->trial_ends_at && $this->trial_ends_at->timestamp > time()) {
			return $this->created_at;
		}
		
		$first_started = $this->trial_ends_at ? $this->trial_ends_at : $this->created_at;
		$period_started = $first_started->copy();
		while (!$period_started->isFuture()) {
			$this->plan->addInterval($period_started);
		}
		
		return $period_started;
	}
	
	public function getPeriodEndsAtAttribute($value)
	{
		if ($this->trial_ends_at && $this->trial_ends_at->timestamp > time()) {
			return $this->trial_ends_at;
		}
		
		return $this->plan->addInterval($this->period_started_at->copy());
	}
	
	public function gracePeriodEnded()
	{
		if (!$this->cancel_at) {
			return false;
		}
		
		if ($this->cancel_at->timestamp > time()) {
			return false;
		}
		
		$this->delete();
		
		return true;
	}
	
	public function process()
	{
		if ($this->cancel_at || $this->trashed()) {
			return;
		}
		
		$first_started = $this->trial_ends_at ? $this->trial_ends_at : $this->created_at;
		$period_started = $first_started->copy();
		
		$oldest_invoice = $this->customer->invoices()
			->where('subscription_id', $this->id)
			->orderBy('period_started_at', 'DESC')
			->first();
		
		$oldest_at = $oldest_invoice
			? $oldest_invoice->period_started_at
			: $period_started->copy()->subDay();
		
		while (!$period_started->isFuture()) {
			if ($period_started->gt($oldest_at)) {
				$this->createInvoice(
					$period_started,
					!$this->customer->cards->isEmpty()
				);
			}
			
			$this->plan->addInterval($period_started);
		}
	}
	
	public function createInvoice($period_started, $paid = true)
	{
		$period_end = $this->plan->addInterval($period_started->copy());
		
		$invoice = Invoice::create(array(
			'customer_id'       => $this->customer->id,
			'subscription_id'   => $this->id,
			'closed'            => 0,
			'paid'              => (int) $paid,
			'coupon_id'         => $this->coupon_id,
			'period_started_at' => $period_started,
			'period_ends_at'    => $period_end,
		));
		
		Invoice\Item::create(array(
			'invoice_id'        => $invoice->id,
			'subscription_id'   => $this->id,
			'description'       => null,
			'amount'            => $this->plan->amount,
			'period_started_at' => $period_started,
			'period_ends_at'    => $period_end,
		));
		
		return $invoice;
	}
}
