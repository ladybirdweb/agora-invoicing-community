<?php namespace Mmanos\Billing\Gateways\Stripe;

use Mmanos\Billing\Gateways\CustomerInterface;
use Illuminate\Support\Arr;
use Stripe_Customer;
use Stripe_Invoice;
use Stripe_Charge;

class Customer implements CustomerInterface
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
	 * Stripe customer object.
	 *
	 * @var Stripe_Customer
	 */
	protected $stripe_customer;
	
	/**
	 * Create a new Stripe customer instance.
	 *
	 * @param Gateway $gateway
	 * @param mixed   $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, $id = null)
	{
		$this->gateway = $gateway;
		
		if ($id instanceof Stripe_Customer) {
			$this->stripe_customer = $id;
			$this->id = $this->stripe_customer->id;
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
	 * Gets info for a customer.
	 * 
	 * @return array|null
	 */
	public function info()
	{
		if (!$this->id) {
			return null;
		}
		
		if (!$this->stripe_customer) {
			$this->stripe_customer = Stripe_Customer::retrieve($this->id);
		}
		
		if (!$this->stripe_customer || property_exists($this->stripe_customer, 'deleted')) {
			return null;
		}
		
		$discounts = array();
		if ($this->stripe_customer->discount) {
			$discounts[] = array(
				'coupon'      => $this->stripe_customer->discount->coupon->id,
				'amount_off'  => $this->stripe_customer->discount->coupon->amount_off,
				'percent_off' => $this->stripe_customer->discount->coupon->percent_off,
				'started_at'  => date('Y-m-d H:i:s', $this->stripe_customer->discount->start),
				'ends_at'     => $this->stripe_customer->discount->end ? date('Y-m-d H:i:s', $this->stripe_customer->discount->end) : null,
			);
		}
		
		return array(
			'id'          => $this->id,
			'description' => $this->stripe_customer->description,
			'email'       => $this->stripe_customer->email,
			'created_at'  => date('Y-m-d H:i:s', $this->stripe_customer->created),
			'discounts'   => $discounts,
		);
	}
	
	/**
	 * Create a new customer.
	 *
	 * @param array $properties
	 * 
	 * @return Customer
	 */
	public function create(array $properties = array())
	{
		$stripe_customer = Stripe_Customer::create(array(
			'description' => Arr::get($properties, 'description') ? Arr::get($properties, 'description') : null,
			'email'       => Arr::get($properties, 'email') ? Arr::get($properties, 'email') : null,
			'coupon'      => Arr::get($properties, 'coupon') ? Arr::get($properties, 'coupon') : null,
			'card'        => Arr::get($properties, 'card_token') ? Arr::get($properties, 'card_token') : null,
		));
		
		$this->id = $stripe_customer->id;
		
		return $this;
	}
	
	/**
	 * Update a customer.
	 *
	 * @param array $properties
	 * 
	 * @return Customer
	 */
	public function update(array $properties = array())
	{
		$this->info();
		
		if (!empty($properties['description'])) {
			$this->stripe_customer->description = $properties['description'];
		}
		if (!empty($properties['email'])) {
			$this->stripe_customer->email = $properties['email'];
		}
		if (!empty($properties['coupon'])) {
			$this->stripe_customer->coupon = $properties['coupon'];
		}
		if (!empty($properties['card_token'])) {
			$this->stripe_customer->card = $properties['card_token'];
		}
		
		$this->stripe_customer->save();
		$this->stripe_customer = null;
		
		return $this;
	}
	
	/**
	 * Delete a customer.
	 * 
	 * @return Customer
	 */
	public function delete()
	{
		$this->info();
		$this->stripe_customer->delete();
		$this->stripe_customer = null;
		return $this;
	}
	
	/**
	 * Gets all subscriptions for a customer.
	 *
	 * @return array
	 */
	public function subscriptions()
	{
		$this->info();
		
		if (!$this->stripe_customer) {
			return array();
		}
		
		$subscriptions = $this->stripe_customer->subscriptions->all();
		
		$subscriptions_array = array();
		foreach ($subscriptions->data as $subscription) {
			$subscriptions_array[] = $this->gateway->subscription($subscription, $this);
		}
		
		return $subscriptions_array;
	}
	
	/**
	 * Gets all credit cards for a customer.
	 *
	 * @return array
	 */
	public function cards()
	{
		$this->info();
		
		if (!$this->stripe_customer) {
			return array();
		}
		
		$cards = $this->stripe_customer->cards->all();
		
		$cards_array = array();
		foreach ($cards->data as $card) {
			$cards_array[] = $this->card($card);
		}
		
		return $cards_array;
	}
	
	/**
	 * Fetch a customer card instance.
	 *
	 * @param mixed $id
	 * 
	 * @return Card
	 */
	public function card($id = null)
	{
		return new Card($this->gateway, $this->getNativeResponse(), $id);
	}
	
	/**
	 * Gets all invoices for a customer.
	 *
	 * @return array
	 */
	public function invoices()
	{
		$this->info();
		
		if (!$this->stripe_customer) {
			return array();
		}
		
		$invoices = Stripe_Invoice::all(array(
			'customer' => $this->id,
			'limit'    => 100,
		));
		
		$invoices_array = array();
		foreach ($invoices->data as $invoice) {
			$invoices_array[] = $this->invoice($invoice);
		}
		
		return $invoices_array;
	}
	
	/**
	 * Fetch an invoice instance.
	 *
	 * @param mixed $id
	 * 
	 * @return Invoice
	 */
	public function invoice($id = null)
	{
		return new Invoice($this->gateway, $this->getNativeResponse(), $id);
	}
	
	/**
	 * Gets all charges for a customer.
	 *
	 * @return array
	 */
	public function charges()
	{
		$this->info();
		
		if (!$this->stripe_customer) {
			return array();
		}
		
		$charges = Stripe_Charge::all(array(
			'customer' => $this->id,
			'limit'    => 100,
		));
		
		$charges_array = array();
		foreach ($charges->data as $charge) {
			$charges_array[] = $this->charge($charge);
		}
		
		return $charges_array;
	}
	
	/**
	 * Fetch a charge instance.
	 *
	 * @param mixed $id
	 * 
	 * @return Charge
	 */
	public function charge($id = null)
	{
		return new Charge($this->gateway, $this->getNativeResponse(), $id);
	}
	
	/**
	 * Gets the native customer response.
	 *
	 * @return Stripe_Customer
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->stripe_customer;
	}
}
