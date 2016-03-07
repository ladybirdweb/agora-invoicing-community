<?php namespace Mmanos\Billing\Gateways\Stripe;

use Mmanos\Billing\Gateways\ChargeInterface;
use Illuminate\Support\Arr;
use Stripe_Customer;
use Stripe_Charge;

class Charge implements ChargeInterface
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
	 * Stripe charge object.
	 *
	 * @var Stripe_Charge
	 */
	protected $stripe_charge;
	
	/**
	 * Create a new Stripe charge instance.
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
		
		if ($id instanceof Stripe_Charge) {
			$this->stripe_charge = $id;
			$this->id = $this->stripe_charge->id;
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
		if (!$this->id || !$this->stripe_customer) {
			return null;
		}
		
		if (!$this->stripe_charge) {
			$this->stripe_charge = Stripe_Charge::retrieve($this->id);
			
			if ($this->stripe_customer->id != $this->stripe_charge->customer) {
				return $this->stripe_charge = null;
			}
		}
		
		if (!$this->stripe_charge) {
			return null;
		}
		
		return array(
			'id'          => $this->id,
			'created_at'  => date('Y-m-d H:i:s', $this->stripe_charge->created),
			'amount'      => $this->stripe_charge->amount,
			'paid'        => $this->stripe_charge->paid,
			'refunded'    => $this->stripe_charge->refunded,
			'captured'    => $this->stripe_charge->captured,
			'card'        => $this->stripe_charge->card ? $this->stripe_charge->card->id : null,
			'invoice_id'  => $this->stripe_charge->invoice,
			'description' => $this->stripe_charge->description,
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
		$card = empty($properties['card']) ? null : $properties['card'];
		if (!empty($properties['card_token'])) {
			$card = $properties['card_token'];
		}
		
		$stripe_charge = Stripe_Charge::create(array(
			'amount'      => $amount,
			'customer'    => $this->stripe_customer->id,
			'currency'    => Arr::get($properties, 'currency', 'usd'),
			'description' => Arr::get($properties, 'description') ? Arr::get($properties, 'description') : null,
			'capture'     => Arr::get($properties, 'capture', true),
			'card'        => $card,
		));
		
		$this->id = $stripe_charge->id;
		
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
		$this->info();
		$this->stripe_charge->capture(array(
			'amount' => Arr::get($properties, 'amount') ? Arr::get($properties, 'amount') : null,
		));
		$this->stripe_charge = null;
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
		$this->stripe_charge->refunds->create(array(
			'amount' => Arr::get($properties, 'amount') ? Arr::get($properties, 'amount') : null,
			'reason' => Arr::get($properties, 'reason') ? Arr::get($properties, 'reason') : null,
		));
		$this->stripe_charge = null;
		return $this;
	}
	
	/**
	 * Gets the native charge response.
	 *
	 * @return Stripe_Charge
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->stripe_charge;
	}
}
