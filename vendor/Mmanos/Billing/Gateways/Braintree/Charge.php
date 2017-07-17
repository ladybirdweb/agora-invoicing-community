<?php namespace Mmanos\Billing\Gateways\Braintree;

use Mmanos\Billing\Gateways\ChargeInterface;
use Illuminate\Support\Arr;
use Braintree_Customer;
use Braintree_Transaction;

class Charge implements ChargeInterface
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
	 * Braintree transaction (charge) object.
	 *
	 * @var Braintree_Transaction
	 */
	protected $braintree_charge;
	
	/**
	 * Create a new Braintree charge instance.
	 *
	 * @param Gateway            $gateway
	 * @param Braintree_Customer $customer
	 * @param mixed              $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, Braintree_Customer $customer = null, $id = null)
	{
		$this->gateway = $gateway;
		$this->braintree_customer = $customer;
		
		if ($id instanceof Braintree_Transaction) {
			$this->braintree_charge = $id;
			$this->id = $this->braintree_charge->id;
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
	 * Gets info for a charge.
	 *
	 * @return array|null
	 */
	public function info()
	{
		if (!$this->id || !$this->braintree_customer) {
			return null;
		}
		
		if (!$this->braintree_charge) {
			$this->braintree_charge = Braintree_Transaction::find($this->id);
			
			if ($this->braintree_charge->customer && $this->braintree_customer->id != $this->braintree_charge->customer['id']) {
				return $this->braintree_charge = null;
			}
		}
		
		if (!$this->braintree_charge) {
			return null;
		}
		
		$paid = in_array($this->braintree_charge->status, array('submitted_for_settlement', 'settled', 'settling'));
		$refunded = empty($this->braintree_charge->refundIds) ? false : true;
		$captured = true;
		if (!$refunded && in_array($this->braintree_charge->status, array('voided'))) {
			$refunded = true;
		}
		if (!in_array($this->braintree_charge->status, array('authorized', 'authorization_expired'))) {
			$captured = false;
		}
		
		return array(
			'id'               => $this->id,
			'created_at'       => date('Y-m-d H:i:s', $this->braintree_charge->createdAt->getTimestamp()),
			'amount'           => ((float) $this->braintree_charge->amount * 100),
			'paid'             => $paid,
			'refunded'         => $refunded,
			'captured'         => $captured,
			'card'             => $this->braintree_charge->creditCardDetails->token,
			'invoice_id'       => $this->id,
			'description'      => null,
		);
	}
	
	/**
	 * Create a new charge.
	 *
	 * @param int   $amount
	 * @param array $properties
	 * 
	 * @return Charge
	 */
	public function create($amount, array $properties = array())
	{
		if (!$token = Arr::get($properties, 'card')) {
			$cards = $this->braintree_customer->creditCards;
			$token = $cards[0]->token;
		}
		
		$braintree_charge = Braintree_Transaction::sale(array(
			'customerId'         => $this->braintree_customer->id,
			'amount'             => number_format($amount / 100, 2),
			'paymentMethodToken' => $token,
		))->transaction;
		
		$this->id = $braintree_charge->id;
		$this->braintree_charge = null;
		
		if (Arr::get($properties, 'capture', true)) {
			$this->capture();
		}
		
		return $this;
	}
	
	/**
	 * Capture a preauthorized charge.
	 *
	 * @param array $properties
	 * 
	 * @return Charge
	 */
	public function capture(array $properties = array())
	{
		if ($amount = Arr::get($properties, 'amount')) {
			Braintree_Transaction::submitForSettlement($this->id, number_format($amount / 100, 2));
		}
		else {
			Braintree_Transaction::submitForSettlement($this->id);
		}
		
		$this->braintree_charge = null;
		
		return $this;
	}
	
	/**
	 * Refund a charge.
	 *
	 * @param array $properties
	 * 
	 * @return Charge
	 */
	public function refund(array $properties = array())
	{
		$this->info();
		
		if (in_array($this->braintree_charge->status, array('settled', 'settling'))) {
			if ($amount = Arr::get($properties, 'amount')) {
				Braintree_Transaction::refund($this->id, number_format($amount / 100, 2));
			}
			else {
				Braintree_Transaction::refund($this->id);
			}
		}
		else {
			Braintree_Transaction::void($this->id);
		}
		
		$this->braintree_charge = null;
		
		return $this;
	}
	
	/**
	 * Gets the native charge response.
	 *
	 * @return Braintree_Transaction
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->braintree_charge;
	}
}
