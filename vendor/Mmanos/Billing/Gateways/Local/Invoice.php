<?php namespace Mmanos\Billing\Gateways\Local;

use Mmanos\Billing\Gateways\InvoiceInterface;
use Illuminate\Support\Arr;

class Invoice implements InvoiceInterface
{
	/**
	 * The gateway instance.
	 *
	 * @var Gateway
	 */
	protected $gateway;
	
	/**
	 * Local customer object.
	 *
	 * @var Models\Customer
	 */
	protected $local_customer;
	
	/**
	 * Primary identifier.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Local invoice object.
	 *
	 * @var Models\Invoice
	 */
	protected $local_invoice;
	
	/**
	 * Create a new Local invoice instance.
	 *
	 * @param Gateway         $gateway
	 * @param Models\Customer $customer
	 * @param mixed           $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, Models\Customer $customer = null, $id = null)
	{
		$this->gateway = $gateway;
		$this->local_customer = $customer;
		
		if ($id instanceof Models\Invoice) {
			$this->local_invoice = $id;
			$this->id = $this->local_invoice->id;
		}
		else if (null !== $id) {
			$this->id = $id;
		}
	}
	
	/**
	 * Gets the id of this instance.
	 * 
	 * @return mixed
	 */
	public function id()
	{
		return $this->id;
	}
	
	/**
	 * Gets info for an invoice.
	 *
	 * @return array|null
	 */
	public function info()
	{
		if (!$this->id || !$this->local_customer) {
			return null;
		}
		
		if (!$this->local_invoice) {
			$this->local_invoice = $this->local_customer->invoices()->where('id', $this->id)->first();
			$this->gateway->apiDelay();
		}
		
		if (!$this->local_invoice) {
			return null;
		}
		
		$discounts = array();
		if ($this->local_invoice->coupon) {
			$started_at = $this->local_invoice->subscription
				? $this->local_invoice->subscription->created_at
				: $this->local_invoice->customer->created_at;
			
			$ends_at = null;
			if ($this->local_invoice->coupon->duration_in_months) {
				if ($this->local_invoice->subscription) {
					$ends_at = $this->local_invoice->subscription->created_at->copy()->addMonths(
						$this->local_invoice->coupon->duration_in_months
					);
				}
				else {
					$ends_at = $this->local_invoice->customer->created_at->copy()->addMonths(
						$this->local_invoice->coupon->duration_in_months
					);
				}
			}
			
			$discounts[] = array(
				'coupon'      => $this->local_invoice->coupon->code,
				'amount_off'  => $this->local_invoice->coupon->amount_off,
				'percent_off' => $this->local_invoice->coupon->percent_off,
				'started_at'  => (string) $started_at,
				'ends_at'     => $ends_at ? (string) $ends_at : null,
			);
		}
		
		$items = array();
		foreach ($this->local_invoice->items as $line) {
			$item = array(
				'id'              => $line->id,
				'amount'          => $line->amount,
				'period_start'    => $line->period_started_at ? (string) $line->period_started_at : null,
				'period_end'      => $line->period_ends_at ? (string) $line->period_ends_at : null,
				'description'     => $line->description,
				'subscription_id' => $line->subscription_id,
				'quantity'        => $line->quantity,
			);
			
			$items[] = $item;
		}
		
		return array(
			'id'               => $this->id,
			'date'             => (string) $this->local_invoice->period_started_at,
			'total'            => $this->local_invoice->subtotal,
			'subtotal'         => $this->local_invoice->subtotal,
			'amount'           => $this->local_invoice->amount,
			'starting_balance' => 0,
			'ending_balance'   => 0,
			'closed'           => $this->local_invoice->closed,
			'paid'             => $this->local_invoice->paid,
			'discounts'        => $discounts,
			'items'            => $items,
		);
	}
	
	/**
	 * Gets the native invoice response.
	 *
	 * @return Models\Invoice
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->local_invoice;
	}
}
