<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;

class Charge
{
	/**
	 * Customer model.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	
	/**
	 * Charge gateway instance.
	 *
	 * @var \Mmanos\Billing\Gateways\ChargeInterface
	 */
	protected $charge;
	
	/**
	 * Charge info array.
	 *
	 * @var array
	 */
	protected $info;
	
	/**
	 * Local copy of the invoice object for this charge.
	 *
	 * @var Invoice
	 */
	protected $invoice;
	
	/**
	 * Create a new CustomerBillableTrait Charge instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model      $model
	 * @param \Mmanos\Billing\Gateways\ChargeInterface $charge
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model, \Mmanos\Billing\Gateways\ChargeInterface $charge)
	{
		$this->model = $model;
		$this->charge = $charge;
		$this->info = $charge->info();
	}
	
	/**
	 * Capture this charge in the billing gateway.
	 *
	 * @param array $properties
	 * 
	 * @return Charge
	 */
	public function capture(array $properties = array())
	{
		if (!$this->model->readyForBilling()) {
			return $this;
		}
		
		$this->charge->capture($properties);
		$this->info = $this->charge->info();
		
		return $this;
	}
	
	/**
	 * Refund this charge in the billing gateway.
	 *
	 * @param array $properties
	 * 
	 * @return Charge
	 */
	public function refund(array $properties = array())
	{
		if (!$this->model->readyForBilling()) {
			return $this;
		}
		
		$this->charge->refund($properties);
		$this->info = $this->charge->info();
		
		return $this;
	}
	
	/**
	 * Return the invoice object associated with this charge.
	 *
	 * @return Invoice
	 */
	public function invoice()
	{
		if (empty($this->invoice_id)) {
			return null;
		}
		
		if (null !== $this->invoice) {
			return $this->invoice ? $this->invoice : null;
		}
		
		$this->invoice = false;
		if ($invoice = $this->model->invoices()->find($this->invoice_id)) {
			$this->invoice = $invoice;
		}
		
		return $this->invoice ? $this->invoice : null;
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
	 * Dynamically check a values existence from the charge.
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
	 * Dynamically get values from the charge.
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
