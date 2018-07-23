<?php namespace Mmanos\Billing\Gateways\Braintree;

use Mmanos\Billing\Gateways\InvoiceInterface;
use Illuminate\Support\Arr;
use Braintree_Customer;
use Braintree_Transaction;

class Invoice implements InvoiceInterface
{
	/**
	 * The gateway instance.
	 *
	 * @var Gateway
	 */
	protected $gateway;
	
	/**
	 * Braintree customer object.
	 *
	 * @var Braintree_Customer
	 */
	protected $braintree_customer;
	
	/**
	 * Primary identifier.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Braintree invoice object.
	 *
	 * @var Braintree_Transaction
	 */
	protected $braintree_invoice;
	
	/**
	 * Create a new Braintree invoice instance.
	 *
	 * @param Gateway         $gateway
	 * @param Braintree_Customer $customer
	 * @param mixed           $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, Braintree_Customer $customer = null, $id = null)
	{
		$this->gateway = $gateway;
		$this->braintree_customer = $customer;
		
		if ($id instanceof Braintree_Transaction) {
			$this->braintree_invoice = $id;
			$this->id = $this->braintree_invoice->id;
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
		if (!$this->id || !$this->braintree_customer) {
			return null;
		}
		
		if (!$this->braintree_invoice) {
			$this->braintree_invoice = Braintree_Transaction::find($this->id);
			
			if ($this->braintree_invoice->customer && $this->braintree_customer->id != $this->braintree_invoice->customer['id']) {
				return $this->braintree_invoice = null;
			}
		}
		
		if (!$this->braintree_invoice) {
			return null;
		}
		
		$discounts = array();
		$total_off = 0;
		foreach ($this->braintree_invoice->discounts as $discount) {
			$discounts[] = array(
				'coupon'      => $discount->id,
				'amount_off'  => ((float) $discount->amount * 100),
				'percent_off' => null,
				'started_at'  => null,
				'ends_at'     => null,
			);
			$total_off += ((float) $discount->amount * 100);
		}
		
		$period_started_at = null;
		$period_ends_at = null;
		if ($this->braintree_invoice->subscriptionDetails) {
			if ($this->braintree_invoice->subscriptionDetails->billingPeriodStartDate) {
				$period_started_at = date('Y-m-d H:i:s', $this->braintree_invoice->subscriptionDetails->billingPeriodStartDate->getTimestamp());
			}
			if ($this->braintree_invoice->subscriptionDetails->billingPeriodEndDate) {
				$period_ends_at = date('Y-m-d H:i:s', $this->braintree_invoice->subscriptionDetails->billingPeriodEndDate->getTimestamp());
			}
		}
		
		$items = array(array(
			'id'              => $this->braintree_invoice->subscriptionId ?: 1,
			'amount'          => ((float) $this->braintree_invoice->amount * 100) + $total_off,
			'period_start'    => $period_started_at,
			'period_end'      => $period_ends_at,
			'description'     => null,
			'subscription_id' => $this->braintree_invoice->subscriptionId,
			'quantity'        => 1,
		));
		
		$paid = in_array($this->braintree_invoice->status, array('submitted_for_settlement', 'settled', 'settling'));
		$closed = $paid || in_array($this->braintree_invoice->status, array('voided'));
		
		return array(
			'id'               => $this->id,
			'date'             => date('Y-m-d H:i:s', $this->braintree_invoice->createdAt->getTimestamp()),
			'total'            => ((float) $this->braintree_invoice->amount * 100),
			'subtotal'         => ((float) $this->braintree_invoice->amount * 100) + $total_off,
			'amount'           => ((float) $this->braintree_invoice->amount * 100),
			'starting_balance' => null,
			'ending_balance'   => null,
			'closed'           => $closed,
			'paid'             => $paid,
			'discounts'        => $discounts,
			'items'            => $items,
		);
	}
	
	/**
	 * Gets the native invoice response.
	 *
	 * @return Braintree_Transaction
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->braintree_invoice;
	}
}
