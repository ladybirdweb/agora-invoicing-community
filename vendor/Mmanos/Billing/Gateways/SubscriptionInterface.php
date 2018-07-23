<?php namespace Mmanos\Billing\Gateways;

interface SubscriptionInterface
{
	/**
	 * Gets the id of this instance.
	 * 
	 * @return mixed
	 */
	public function id();
	
	/**
	 * Gets info for a subscription.
	 *
	 * @return array|null
	 */
	public function info();
	
	/**
	 * Create a new subscription.
	 *
	 * @param mixed $plan
	 * @param array $properties
	 * 
	 * @return SubscriptionInterface
	 */
	public function create($plan, array $properties = array());
	
	/**
	 * Update a subscription.
	 *
	 * @param array $properties
	 * 
	 * @return SubscriptionInterface
	 */
	public function update(array $properties = array());
	
	/**
	 * Cancel a subscription.
	 *
	 * @param bool $at_period_end
	 * 
	 * @return SubscriptionInterface
	 */
	public function cancel($at_period_end = true);
}
