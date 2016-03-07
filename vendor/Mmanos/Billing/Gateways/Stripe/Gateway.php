<?php namespace Mmanos\Billing\Gateways\Stripe;

use Mmanos\Billing\Gateways\GatewayInterface;
use Mmanos\Billing\Gateways\CustomerInterface;
use Illuminate\Support\Facades\Config;
use Stripe;

class Gateway implements GatewayInterface
{
	/**
	 * Array of gateway connection/configuration properties.
	 *
	 * @var array
	 */
	protected $connection;
	
	/**
	 * Create a new Stripe gateway instance.
	 *
	 * @return void
	 */
	public function __construct($connection = null)
	{
		if (null === $connection) {
			$connection = Config::get('laravel-billing::gateways.stripe');
		}
		
		$this->connection = $connection;
		
		Stripe::setApiKey($connection['secret']);
	}
	
	/**
	 * Fetch a customer instance.
	 *
	 * @param mixed $id
	 * 
	 * @return Customer
	 */
	public function customer($id = null)
	{
		return new Customer($this, $id);
	}
	
	/**
	 * Fetch a subscription instance.
	 *
	 * @param mixed    $id
	 * @param Customer $customer
	 * 
	 * @return Subscription
	 */
	public function subscription($id = null, CustomerInterface $customer = null)
	{
		if ($customer) {
			$customer = $customer->getNativeResponse();
		}
		
		return new Subscription($this, $customer, $id);
	}
	
	/**
	 * Fetch a charge instance.
	 *
	 * @param mixed             $id
	 * @param CustomerInterface $customer
	 * 
	 * @return Charge
	 */
	public function charge($id = null, CustomerInterface $customer = null)
	{
		if ($customer) {
			$customer = $customer->getNativeResponse();
		}
		
		return new Charge($this, $customer, $id);
	}
}
