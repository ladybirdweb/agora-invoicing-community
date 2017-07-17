<?php namespace Mmanos\Billing\Gateways\Local;

use Mmanos\Billing\Gateways\CustomerInterface;
use Illuminate\Support\Arr;

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
	 * Local customer object.
	 *
	 * @var Models\Customer
	 */
	protected $local_customer;
	
	/**
	 * Create a new Local customer instance.
	 *
	 * @param Gateway $gateway
	 * @param mixed   $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, $id = null)
	{
		$this->gateway = $gateway;
		
		if ($id instanceof Models\Customer) {
			$this->local_customer = $id;
			$this->id = $this->local_customer->id;
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
		
		if (!$this->local_customer) {
			$this->local_customer = Models\Customer::find($this->id);
			$this->gateway->apiDelay();
		}
		
		if (!$this->local_customer) {
			return null;
		}
		
		$discounts = array();
		if ($this->local_customer->coupon) {
			$ends_at = null;
			if ($this->local_customer->coupon->duration_in_months) {
				$ends_at = $this->local_customer->created_at->copy()->addMonths(
					$this->local_customer->coupon->duration_in_months
				);
			}
			
			$discounts[] = array(
				'coupon'      => $this->local_customer->coupon->code,
				'amount_off'  => $this->local_customer->coupon->amount_off,
				'percent_off' => $this->local_customer->coupon->percent_off,
				'started_at'  => (string) $this->local_customer->created_at,
				'ends_at'     => (string) $ends_at,
			);
		}
		
		return array(
			'id'          => $this->id,
			'description' => $this->local_customer->description,
			'email'       => $this->local_customer->email,
			'created_at'  => (string) $this->local_customer->created_at,
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
		$coupon_id = null;
		if ($coupon = Arr::get($properties, 'coupon')) {
			$coupon_id = Models\Coupon::where('code', $coupon)->first()->id;
		}
		
		$this->local_customer = Models\Customer::create(array(
			'description' => Arr::get($properties, 'description'),
			'email'       => Arr::get($properties, 'email'),
			'coupon_id'   => $coupon_id,
		));
		
		$this->gateway->apiDelay();
		
		$this->id = $this->local_customer->id;
		
		if ($token = Arr::get($properties, 'card_token')) {
			$this->card()->create($token);
		}
		
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
			$this->local_customer->description = $properties['description'];
		}
		if (!empty($properties['email'])) {
			$this->local_customer->email = $properties['email'];
		}
		if (!empty($properties['coupon'])) {
			if ($coupon_model = Models\Coupon::where('code', $properties['coupon'])->first()) {
				$this->local_customer->coupon_id = $coupon_model->id;
			}
		}
		
		$this->local_customer->save();
		
		if (!empty($properties['card_token'])) {
			$token = $properties['card_token'];
			if ($card = $this->local_customer->cards->first()) {
				$this->card($card)->update(json_decode($token, true));
			}
			else {
				$this->card()->create($token);
			}
		}
		
		$this->gateway->apiDelay();
		$this->local_subscription = null;
		
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
		foreach ($this->subscriptions() as $subscription) {
			$subscription->cancel(false);
		}
		$this->local_customer->delete();
		$this->gateway->apiDelay();
		$this->local_customer = null;
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
		
		if (!$this->local_customer) {
			return array();
		}
		
		$subscriptions = $this->local_customer->subscriptions;
		$this->gateway->apiDelay();
		
		$subscriptions_array = array();
		foreach ($subscriptions as $subscription) {
			if ($subscription->gracePeriodEnded()) {
				continue;
			}
			
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
		
		if (!$this->local_customer) {
			return array();
		}
		
		$cards = $this->local_customer->cards;
		$this->gateway->apiDelay();
		
		$cards_array = array();
		foreach ($cards as $card) {
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
		
		if (!$this->local_customer) {
			return array();
		}
		
		foreach ($this->local_customer->subscriptions as $subscription) {
			if ($subscription->gracePeriodEnded()) {
				continue;
			}
			
			$subscription->process();
		}
		
		$invoices = $this->local_customer->invoices()->limit(100)->get();
		$this->gateway->apiDelay();
		
		$invoices_array = array();
		foreach ($invoices as $invoice) {
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
		
		if (!$this->local_customer) {
			return array();
		}
		
		$charges = $this->local_customer->charges()->limit(100)->get();
		$this->gateway->apiDelay();
		
		$charges_array = array();
		foreach ($charges as $charge) {
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
	 * @return Models\Customer
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->local_customer;
	}
}
