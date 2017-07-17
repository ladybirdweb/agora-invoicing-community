<?php namespace Mmanos\Billing\Gateways;

interface CustomerInterface
{
	/**
	 * Gets the id of this instance.
	 * 
	 * @return mixed
	 */
	public function id();
	
	/**
	 * Gets info for a customer.
	 * 
	 * @return array|null
	 */
	public function info();
	
	/**
	 * Create a new customer.
	 *
	 * @param array $properties
	 * 
	 * @return CustomerInterface
	 */
	public function create(array $properties = array());
	
	/**
	 * Update a customer.
	 *
	 * @param array $properties
	 * 
	 * @return CustomerInterface
	 */
	public function update(array $properties = array());
	
	/**
	 * Delete a customer.
	 * 
	 * @return CustomerInterface
	 */
	public function delete();
	
	/**
	 * Gets all subscriptions for a customer.
	 *
	 * @return array
	 */
	public function subscriptions();
	
	/**
	 * Gets all credit cards for a customer.
	 *
	 * @return array
	 */
	public function cards();
	
	/**
	 * Fetch a customer card instance.
	 *
	 * @param mixed $id
	 * 
	 * @return CardInterface
	 */
	public function card($id = null);
	
	/**
	 * Gets all invoices for a customer.
	 *
	 * @return array
	 */
	public function invoices();
	
	/**
	 * Fetch an invoice instance.
	 *
	 * @param mixed $id
	 * 
	 * @return InvoiceInterface
	 */
	public function invoice($id = null);
	
	/**
	 * Gets all charges for a customer.
	 *
	 * @return array
	 */
	public function charges();
	
	/**
	 * Fetch a charge instance.
	 *
	 * @param mixed $id
	 * 
	 * @return ChargeInterface
	 */
	public function charge($id = null);
}
