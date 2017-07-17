<?php namespace Mmanos\Billing\Gateways;

interface CardInterface
{
	/**
	 * Gets the id of this instance.
	 * 
	 * @return mixed
	 */
	public function id();
	
	/**
	 * Gets info for a card.
	 *
	 * @return array|null
	 */
	public function info();
	
	/**
	 * Create a new card.
	 *
	 * @param string $card_token
	 * 
	 * @return CardInterface
	 */
	public function create($card_token);
	
	/**
	 * Update a card.
	 *
	 * @param array $properties
	 * 
	 * @return CardInterface
	 */
	public function update(array $properties = array());
	
	/**
	 * Delete a card.
	 *
	 * @return CardInterface
	 */
	public function delete();
}
