<?php namespace Mmanos\Billing\Gateways\Local;

use Mmanos\Billing\Gateways\GatewayInterface;
use Mmanos\Billing\Gateways\CustomerInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class Gateway implements GatewayInterface
{
	/**
	 * Array of gateway connection/configuration properties.
	 *
	 * @var array
	 */
	protected $connection;
	
	/**
	 * Create a new Local gateway instance.
	 *
	 * @return void
	 */
	public function __construct($connection = null)
	{
		if (null === $connection) {
			$connection = Config::get('laravel-billing::gateways.local');
		}
		
		$this->connection = $connection;
		
		Config::set('database.connections.billinglocal', Arr::get($connection, 'database'));
		
		$path = Arr::get($connection, 'database.database');
		if (!file_exists($path) && is_dir(dirname($path))) {
			touch($path);
		}
		
		if (!Schema::connection('billinglocal')->hasTable('customers')) {
			include_once 'Models/migration.php';
		}
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
	
	/**
	 * Simulate an API delay.
	 *
	 * @return void
	 */
	public function apiDelay()
	{
		$delay = Config::get('laravel-billing::gateways.local.api_delay_ms');
		if (empty($delay)) {
			return;
		}
		
		$ms = $delay * 1000;
		$threshold = $ms * .5;
		$sleep = rand($ms-$threshold, $ms+$threshold);
		
		usleep($sleep);
	}
}
