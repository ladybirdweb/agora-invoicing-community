<?php namespace Mmanos\Billing\Gateways;

interface ChargeInterface
{
	/**
	 * Gets the id of this instance.
	 * 
	 * @return mixed
	 */
	public function id();
	
	/**
	 * Gets info for a charge.
	 *
	 * @return array|null
	 */
	public function info();
	
	/**
	 * Create a new charge.
	 *
	 * @param int   $amount
	 * @param array $properties
	 * 
	 * @return ChargeInterface
	 */
	public function create($amount, array $properties = array());
	
	/**
	 * Capture a preauthorized charge.
	 *
	 * @param array $properties
	 * 
	 * @return ChargeInterface
	 */
	public function capture(array $properties = array());
	
	/**
	 * Refund a charge.
	 *
	 * @param array $properties
	 * 
	 * @return ChargeInterface
	 */
	public function refund(array $properties = array());
}
