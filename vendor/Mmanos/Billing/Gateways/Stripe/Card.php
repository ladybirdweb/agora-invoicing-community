<?php namespace Mmanos\Billing\Gateways\Stripe;

use Mmanos\Billing\Gateways\CardInterface;
use Illuminate\Support\Arr;
use Stripe_Customer;
use Stripe_Card;

class Card implements CardInterface
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
	 * Stripe card object.
	 *
	 * @var Stripe_Card
	 */
	protected $stripe_card;
	
	/**
	 * Create a new Stripe card instance.
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
		
		if ($id instanceof Stripe_Card) {
			$this->stripe_card = $id;
			$this->id = $this->stripe_card->id;
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
	 * Gets info for a card.
	 *
	 * @return array|null
	 */
	public function info()
	{
		if (!$this->id || !$this->stripe_customer) {
			return null;
		}
		
		if (!$this->stripe_card) {
			$this->stripe_card = $this->stripe_customer->cards->retrieve($this->id);
		}
		
		if (!$this->stripe_card) {
			return null;
		}
		
		return array(
			'id'              => $this->id,
			'last4'           => $this->stripe_card->last4,
			'brand'           => $this->stripe_card->brand,
			'exp_month'       => $this->stripe_card->exp_month,
			'exp_year'        => $this->stripe_card->exp_year,
			'name'            => $this->stripe_card->name,
			'address_line1'   => $this->stripe_card->address_line1,
			'address_line2'   => $this->stripe_card->address_line2,
			'address_city'    => $this->stripe_card->address_city,
			'address_state'   => $this->stripe_card->address_state,
			'address_zip'     => $this->stripe_card->address_zip,
			'address_country' => $this->stripe_card->address_country,
		);
	}
	
	/**
	 * Create a new card.
	 *
	 * @param string $card_token
	 * 
	 * @return Card
	 */
	public function create($card_token)
	{
		$stripe_card = $this->stripe_customer->cards->create(array(
			'card' => $card_token,
		));
		
		$this->id = $stripe_card->id;
		
		return $this;
	}
	
	/**
	 * Update a card.
	 *
	 * @param array $properties
	 * 
	 * @return Card
	 */
	public function update(array $properties = array())
	{
		$this->info();
		
		if (!empty($properties['name'])) {
			$this->stripe_card->name = $properties['name'];
		}
		if (!empty($properties['exp_month'])) {
			$this->stripe_card->exp_month = $properties['exp_month'];
		}
		if (!empty($properties['exp_year'])) {
			$this->stripe_card->exp_year = $properties['exp_year'];
		}
		if (!empty($properties['address_line1'])) {
			$this->stripe_card->address_line1 = $properties['address_line1'];
		}
		if (!empty($properties['address_line2'])) {
			$this->stripe_card->address_line2 = $properties['address_line2'];
		}
		if (!empty($properties['address_city'])) {
			$this->stripe_card->address_city = $properties['address_city'];
		}
		if (!empty($properties['address_state'])) {
			$this->stripe_card->address_state = $properties['address_state'];
		}
		if (!empty($properties['address_zip'])) {
			$this->stripe_card->address_zip = $properties['address_zip'];
		}
		if (!empty($properties['address_country'])) {
			$this->stripe_card->address_country = $properties['address_country'];
		}
		
		$this->stripe_card->save();
		$this->stripe_card = null;
		
		return $this;
	}
	
	/**
	 * Delete a card.
	 *
	 * @return Card
	 */
	public function delete()
	{
		$this->info();
		$this->stripe_card->delete();
		$this->stripe_card = null;
		return $this;
	}
	
	/**
	 * Gets the native card response.
	 *
	 * @return Stripe_Card
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->stripe_card;
	}
}
