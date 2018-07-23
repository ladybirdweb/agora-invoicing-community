<?php namespace Mmanos\Billing\Gateways\Local;

use Mmanos\Billing\Gateways\CardInterface;
use Illuminate\Support\Arr;

class Card implements CardInterface
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
	 * Local card object.
	 *
	 * @var Models\Card
	 */
	protected $local_card;
	
	/**
	 * Create a new Local card instance.
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
		
		if ($id instanceof Models\Card) {
			$this->local_card = $id;
			$this->id = $this->local_card->id;
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
		if (!$this->id || !$this->local_customer) {
			return null;
		}
		
		if (!$this->local_card) {
			$this->local_card = $this->local_customer->cards()->where('id', $this->id)->first();
			$this->gateway->apiDelay();
		}
		
		if (!$this->local_card) {
			return null;
		}
		
		return array(
			'id'              => $this->id,
			'last4'           => $this->local_card->last4,
			'brand'           => $this->local_card->brand,
			'exp_month'       => $this->local_card->exp_month,
			'exp_year'        => $this->local_card->exp_year,
			'name'            => $this->local_card->name,
			'address_line1'   => $this->local_card->address_line1,
			'address_line2'   => $this->local_card->address_line2,
			'address_city'    => $this->local_card->address_city,
			'address_state'   => $this->local_card->address_state,
			'address_zip'     => $this->local_card->address_zip,
			'address_country' => $this->local_card->address_country,
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
		$properties = json_decode($card_token, true);
		
		$this->local_card = Models\Card::create(array(
			'customer_id'     => $this->local_customer->id,
			'last4'           => Arr::get($properties, 'last4'),
			'brand'           => Arr::get($properties, 'brand', 'Visa'),
			'exp_month'       => Arr::get($properties, 'exp_month'),
			'exp_year'        => Arr::get($properties, 'exp_year'),
			'name'            => Arr::get($properties, 'name'),
			'address_line1'   => Arr::get($properties, 'address_line1'),
			'address_line2'   => Arr::get($properties, 'address_line2'),
			'address_city'    => Arr::get($properties, 'address_city'),
			'address_state'   => Arr::get($properties, 'address_state'),
			'address_zip'     => Arr::get($properties, 'address_zip'),
			'address_country' => Arr::get($properties, 'address_country'),
		));
		
		$this->gateway->apiDelay();
		
		$this->id = $this->local_card->id;
		
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
		
		if (!empty($properties['brand'])) {
			$this->local_card->brand = $properties['brand'];
		}
		if (!empty($properties['last4'])) {
			$this->local_card->last4 = $properties['last4'];
		}
		if (!empty($properties['name'])) {
			$this->local_card->name = $properties['name'];
		}
		if (!empty($properties['exp_month'])) {
			$this->local_card->exp_month = $properties['exp_month'];
		}
		if (!empty($properties['exp_year'])) {
			$this->local_card->exp_year = $properties['exp_year'];
		}
		if (!empty($properties['address_line1'])) {
			$this->local_card->address_line1 = $properties['address_line1'];
		}
		if (!empty($properties['address_line2'])) {
			$this->local_card->address_line2 = $properties['address_line2'];
		}
		if (!empty($properties['address_city'])) {
			$this->local_card->address_city = $properties['address_city'];
		}
		if (!empty($properties['address_state'])) {
			$this->local_card->address_state = $properties['address_state'];
		}
		if (!empty($properties['address_zip'])) {
			$this->local_card->address_zip = $properties['address_zip'];
		}
		if (!empty($properties['address_country'])) {
			$this->local_card->address_country = $properties['address_country'];
		}
		
		$this->local_card->save();
		$this->gateway->apiDelay();
		
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
		$this->local_card->delete();
		$this->gateway->apiDelay();
		$this->local_card = null;
		return $this;
	}
	
	/**
	 * Gets the native card response.
	 *
	 * @return Models\Card
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->local_card;
	}
}
