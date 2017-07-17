<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;

class InvoiceItem
{
	/**
	 * Customer model.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	
	/**
	 * Invoice gateway instance.
	 *
	 * @var \Mmanos\Billing\Gateways\InvoiceInterface
	 */
	protected $invoice;
	
	/**
	 * Invoice item info array.
	 *
	 * @var array
	 */
	protected $info;
	
	/**
	 * Local copy of the subscription model for this invoice item.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $subscription;
	
	/**
	 * Create a new CustomerBillableTrait Invoice Item instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model       $model
	 * @param \Mmanos\Billing\Gateways\InvoiceInterface $invoice
	 * @param array                                     $item
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model, \Mmanos\Billing\Gateways\InvoiceInterface$invoice, array $item)
	{
		$this->model = $model;
		$this->invoice = $invoice;
		$this->info = $item;
	}
	
	/**
	 * Return the subscription helper object associated with this invoice item.
	 *
	 * @return \Mmanos\Billing\SubscriptionBillableTrait\Subscription
	 */
	public function subscription()
	{
		if (empty($this->subscription_id)) {
			return null;
		}
		
		if (null !== $this->subscription) {
			return $this->subscription ? $this->subscription : null;
		}
		
		$this->subscription = false;
		if ($subscription = $this->model->subscriptions()->find($this->subscription_id)) {
			$this->subscription = $subscription;
		}
		
		return $this->subscription ? $this->subscription : null;
	}
	
	/**
	 * Convert this instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->info;
	}
	
	/**
	 * Dynamically check a values existence from the invoice.
	 *
	 * @param string $key
	 * 
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->info[$key]);
	}
	
	/**
	 * Dynamically get values from the invoice.
	 *
	 * @param string $key
	 * 
	 * @return mixed
	 */
	public function __get($key)
	{
		return isset($this->info[$key]) ? $this->info[$key] : null;
	}
}
