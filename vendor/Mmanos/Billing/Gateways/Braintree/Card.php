<?php namespace Mmanos\Billing\Gateways\Braintree;

use Mmanos\Billing\Gateways\CardInterface;
use Illuminate\Support\Arr;
use Braintree_Customer;
use Braintree_CreditCard;

class Card implements CardInterface
{
	/**
	 * The gateway instance.
	 *
	 * @var Gateway
	 */
	protected $gateway;
	
	/**
	 * Primary identifier.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Braintree card object.
	 *
	 * @var Braintree_CreditCard
	 */
	protected $braintree_card;
	
	/**
	 * Create a new Braintree card instance.
	 *
	 * @param Gateway $gateway
	 * @param mixed   $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, $id = null)
	{
		$this->gateway = $gateway;
		
		if ($id instanceof Braintree_CreditCard) {
			$this->braintree_card = $id;
			$this->id = $this->braintree_card->token;
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
		if (!$this->id) {
			return null;
		}
		
		if (!$this->braintree_card) {
			$this->braintree_card = Braintree_CreditCard::find($this->id);
		}
		
		if (!$this->braintree_card) {
			return null;
		}
		
		$return = array(
			'id'              => $this->id,
			'last4'           => $this->braintree_card->last4,
			'brand'           => $this->braintree_card->cardType,
			'exp_month'       => $this->braintree_card->expirationMonth,
			'exp_year'        => $this->braintree_card->expirationYear,
			'name'            => $this->braintree_card->cardholderName,
			'address_line1'   => null,
			'address_line2'   => null,
			'address_city'    => null,
			'address_state'   => null,
			'address_zip'     => null,
			'address_country' => null,
		);
		
		if ($this->braintree_card->billingAddress) {
			$return = array_merge($return, array(
				'address_line1'   => $this->braintree_card->billingAddress->streetAddress,
				'address_line2'   => $this->braintree_card->billingAddress->extendedAddress,
				'address_city'    => $this->braintree_card->billingAddress->locality,
				'address_state'   => $this->braintree_card->billingAddress->region,
				'address_zip'     => $this->braintree_card->billingAddress->postalCode,
				'address_country' => $this->braintree_card->billingAddress->countryName,
			));
		}
		
		return $return;
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
		// Braintree does not support creating a card from a token.
		// You must use their transparent redirect.
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
		$props = array();
		
		if (!empty($properties['name'])) {
			$props['cardholderName'] = $properties['name'];
		}
		if (!empty($properties['exp_month'])) {
			$props['expirationMonth'] = $properties['exp_month'];
		}
		if (!empty($properties['exp_year'])) {
			$props['expirationYear'] = $properties['exp_year'];
		}
		if (!empty($properties['address_line1'])) {
			$props['billingAddress']['streetAddress'] = $properties['address_line1'];
		}
		if (!empty($properties['address_line2'])) {
			$props['billingAddress']['extendedAddress'] = $properties['address_line2'];
		}
		if (!empty($properties['address_city'])) {
			$props['billingAddress']['locality'] = $properties['address_city'];
		}
		if (!empty($properties['address_state'])) {
			$props['billingAddress']['region'] = $properties['address_state'];
		}
		if (!empty($properties['address_zip'])) {
			$props['billingAddress']['postalCode'] = $properties['address_zip'];
		}
		if (!empty($properties['address_country'])) {
			$props['billingAddress']['countryName'] = $properties['address_country'];
		}
		
		if (!empty($props['billingAddress'])) {
			$props['billingAddress']['options'] = array('updateExisting' => true);
		}
		
		Braintree_CreditCard::update($this->id, $props);
		$this->braintree_card = null;
		
		return $this;
	}
	
	/**
	 * Delete a card.
	 *
	 * @return Card
	 */
	public function delete()
	{
		Braintree_CreditCard::delete($this->id);
		$this->braintree_card = null;
		return $this;
	}
	
	/**
	 * Gets the native card response.
	 *
	 * @return Braintree_CreditCard
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->braintree_card;
	}
}
