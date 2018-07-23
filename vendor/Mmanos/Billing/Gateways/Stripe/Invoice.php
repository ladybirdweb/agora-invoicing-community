<?php namespace Mmanos\Billing\Gateways\Stripe;

use Mmanos\Billing\Gateways\InvoiceInterface;
use Illuminate\Support\Arr;
use Stripe_Customer;
use Stripe_Invoice;

class Invoice implements InvoiceInterface
{
	/**
	 * The gateway instance.
	 *
	 * @var Gateway
	 */
	protected $gateway;
	
	/**
	 * Stripe customer object.
	 *
	 * @var Stripe_Customer
	 */
	protected $stripe_customer;
	
	/**
	 * Primary identifier.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Stripe invoice object.
	 *
	 * @var Stripe_Invoice
	 */
	protected $stripe_invoice;
	
	/**
	 * Create a new Stripe invoice instance.
	 *
	 * @param Gateway         $gateway
	 * @param Stripe_Customer $customer
	 * @param mixed           $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, Stripe_Customer $customer = null, $id = null)
	{
		$this->gateway = $gateway;
		$this->stripe_customer = $customer;
		
		if ($id instanceof Stripe_Invoice) {
			$this->stripe_invoice = $id;
			$this->id = $this->stripe_invoice->id;
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
		if (!$this->id || !$this->stripe_customer) {
			return null;
		}
		
		if (!$this->stripe_invoice) {
			$this->stripe_invoice = Stripe_Invoice::retrieve($this->id);
			
			if ($this->stripe_customer->id != $this->stripe_invoice->customer) {
				return $this->stripe_invoice = null;
			}
		}
		
		if (!$this->stripe_invoice) {
			return null;
		}
		
		$discounts = array();
		if ($this->stripe_invoice->discount) {
			$discounts[] = array(
				'coupon'      => $this->stripe_invoice->discount->coupon->id,
				'amount_off'  => $this->stripe_invoice->discount->coupon->amount_off,
				'percent_off' => $this->stripe_invoice->discount->coupon->percent_off,
				'started_at'  => date('Y-m-d H:i:s', $this->stripe_invoice->discount->start),
				'ends_at'     => $this->stripe_invoice->discount->end ? date('Y-m-d H:i:s', $this->stripe_invoice->discount->end) : null,
			);
		}
		
		$items = array();
		foreach ($this->stripe_invoice->lines->data as $line) {
			$item = array(
				'id'              => $line->id,
				'amount'          => $line->amount,
				'period_start'    => null,
				'period_end'      => null,
				'description'     => $line->description,
				'subscription_id' => ('subscription' == $line->type) ? $line->id : $line->subscription,
				'quantity'        => $line->quantity,
			);
			
			if ($line->period && $line->period->start) {
				$item['period_start'] = date('Y-m-d H:i:s', $line->period->start);
			}
			if ($line->period && $line->period->end) {
				$item['period_end'] = date('Y-m-d H:i:s', $line->period->end);
			}
			
			$items[] = $item;
		}
		
		return array(
			'id'               => $this->id,
			'date'             => date('Y-m-d H:i:s', $this->stripe_invoice->date),
			'total'            => $this->stripe_invoice->total,
			'subtotal'         => $this->stripe_invoice->subtotal,
			'amount'           => $this->stripe_invoice->amount_due,
			'starting_balance' => $this->stripe_invoice->starting_balance,
			'ending_balance'   => $this->stripe_invoice->ending_balance,
			'closed'           => $this->stripe_invoice->closed,
			'paid'             => $this->stripe_invoice->paid,
			'discounts'        => $discounts,
			'items'            => $items,
		);
	}
	
	/**
	 * Gets the native invoice response.
	 *
	 * @return Stripe_Invoice
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->stripe_invoice;
	}
}
