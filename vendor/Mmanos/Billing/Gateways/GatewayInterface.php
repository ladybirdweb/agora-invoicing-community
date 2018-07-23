<?php namespace Mmanos\Billing\Gateways;

interface GatewayInterface
{
	/**
	 * Fetch a customer instance.
	 *
	 * @param mixed $id
	 * 
	 * @return CustomerInterface
	 */
	public function customer($id = null);
	
	/**
	 * Fetch a subscription instance.
	 *
	 * @param mixed             $id
	 * @param CustomerInterface $customer
	 * 
	 * @return SubscriptionInterface
	 */
	public function subscription($id = null, CustomerInterface $customer = null);
	
	/**
	 * Fetch a charge instance.
	 *
	 * @param mixed             $id
	 * @param CustomerInterface $customer
	 * 
	 * @return ChargeInterface
	 */
	public function charge($id = null, CustomerInterface $customer = null);
}
