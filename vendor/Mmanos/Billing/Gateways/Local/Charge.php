<?php namespace Mmanos\Billing\Gateways\Local;

use Mmanos\Billing\Gateways\ChargeInterface;
use Illuminate\Support\Arr;

class Charge implements ChargeInterface
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
	 * Local charge object.
	 *
	 * @var Models\Charge
	 */
	protected $local_charge;
	
	/**
	 * Create a new Local charge instance.
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
		
		if ($id instanceof Models\Charge) {
			$this->local_charge = $id;
			$this->id = $this->local_charge->id;
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
		if (!$this->id || !$this->local_customer) {
			return null;
		}
		
		if (!$this->local_charge) {
			$this->local_charge = $this->local_customer->charges()->where('id', $this->id)->first();
			$this->gateway->apiDelay();
		}
		
		if (!$this->local_charge) {
			return null;
		}
		
		return array(
			'id'          => $this->id,
			'created_at'  => (string) $this->local_charge->created_at,
			'amount'      => $this->local_charge->amount,
			'paid'        => $this->local_charge->paid,
			'refunded'    => $this->local_charge->refunded,
			'captured'    => $this->local_charge->captured,
			'card'        => $this->local_charge->card_id,
			'invoice_id'  => null,
			'description' => $this->local_charge->description,
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
		$card_id = empty($properties['card']) ? null : $properties['card'];
		if (!empty($properties['card_token'])) {
			$card_id = $this->gateway->customer($this->local_customer)
				->card()
				->create($properties['card_token'])
				->id();
		}
		if (!$card_id) {
			$card_id = $this->local_customer->cards->first()->id;
		}
		
		$this->local_charge = Models\Charge::create(array(
			'customer_id' => $this->local_customer->id,
			'amount'      => $amount,
			'card_id'     => Models\Card::find($card_id)->id,
			'description' => Arr::get($properties, 'description'),
			'paid'        => false,
			'captured'    => false,
			'refunded'    => false,
		));
		
		$this->gateway->apiDelay();
		
		$this->id = $this->local_charge->id;
		
		if (Arr::get($properties, 'capture', true)) {
			$this->local_charge->capture();
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
		$this->info();
		$this->local_charge->capture();
		$this->gateway->apiDelay();
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
		$this->local_charge->refund();
		$this->gateway->apiDelay();
		return $this;
	}
	
	/**
	 * Gets the native charge response.
	 *
	 * @return Models\Charge
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->local_charge;
	}
}
