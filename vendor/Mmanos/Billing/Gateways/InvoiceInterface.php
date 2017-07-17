<?php namespace Mmanos\Billing\Gateways;

interface InvoiceInterface
{
	/**
	 * Gets the id of this instance.
	 * 
	 * @return mixed
	 */
	public function id();
	
	/**
	 * Gets info for an invoice.
	 *
	 * @return array|null
	 */
	public function info();
}
