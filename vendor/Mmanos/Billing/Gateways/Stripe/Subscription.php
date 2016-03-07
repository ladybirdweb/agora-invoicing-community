<?php namespace Mmanos\Billing\Gateways\Stripe;

use Mmanos\Billing\Gateways\SubscriptionInterface;
use Illuminate\Support\Arr;
use Stripe_Customer;
use Stripe_Subscription;

class Subscription implements SubscriptionInterface
{
	/**
	 * The gateway instance.
	 *
	 * @var Gateway
	 */
	protected $gateway;
	
	/**
	 * Stripe customer object.
	 *
	 * @var Stripe_Customer
	 */
	protected $stripe_customer;
	
	/**
	 * Primary identifier.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Stripe subscription object.
	 *
	 * @var Stripe_Subscription
	 */
	protected $stripe_subscription;
	
	/**
	 * Create a new Stripe subscription instance.
	 *
	 * @param Gateway         $gateway
	 * @param Stripe_Customer $customer
	 * @param mixed           $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, Stripe_Customer $customer = null, $id = null)
	{
		$this->gateway = $gateway;
		$this->stripe_customer = $customer;
		
		if ($id instanceof Stripe_Subscription) {
			$this->stripe_subscription = $id;
			$this->id = $this->stripe_subscription->id;
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
	 * Gets info for a subscription.
	 *
	 * @return array|null
	 */
	public function info()
	{
		if (!$this->id || !$this->stripe_customer) {
			return null;
		}
		
		if (!$this->stripe_subscription) {
			$this->stripe_subscription = $this->stripe_customer->subscriptions->retrieve($this->id);
		}
		
		if (!$this->stripe_subscription) {
			return null;
		}
		
		$trial_end = $this->stripe_subscription->trial_end;
		
		$discounts = array();
		if ($this->stripe_subscription->discount) {
			$discounts[] = array(
				'coupon'      => $this->stripe_subscription->discount->coupon->id,
				'amount_off'  => $this->stripe_subscription->discount->coupon->amount_off,
				'percent_off' => $this->stripe_subscription->discount->coupon->percent_off,
				'started_at'  => date('Y-m-d H:i:s', $this->stripe_subscription->discount->start),
				'ends_at'     => $this->stripe_subscription->discount->end ? date('Y-m-d H:i:s', $this->stripe_subscription->discount->end) : null,
			);
		}
		
		return array(
			'id'                => $this->id,
			'plan'              => $this->stripe_subscription->plan->id,
			'amount'            => $this->stripe_subscription->plan->amount,
			'interval'          => $this->stripe_subscription->plan->interval,
			'active'            => ('canceled' != $this->stripe_subscription->status && !$this->stripe_subscription->cancel_at_period_end),
			'quantity'          => $this->stripe_subscription->quantity,
			'started_at'        => date('Y-m-d H:i:s', $this->stripe_subscription->start),
			'period_started_at' => date('Y-m-d H:i:s', $this->stripe_subscription->current_period_start),
			'period_ends_at'    => date('Y-m-d H:i:s', $this->stripe_subscription->current_period_end),
			'trial_ends_at'     => $trial_end ? date('Y-m-d H:i:s', $trial_end) : null,
			'card'              => $this->stripe_customer->default_card,
			'discounts'         => $discounts,
		);
	}
	
	/**
	 * Create a new subscription.
	 *
	 * @param mixed $plan
	 * @param array $properties
	 * 
	 * @return Subscription
	 */
	public function create($plan, array $properties = array())
	{
		$trial_end = null;
		if (!empty($properties['trial_ends_at'])) {
			$trial_end = strtotime($properties['trial_ends_at']);
			if ($trial_end <= time()) {
				$trial_end = 'now';
			}
		}
		
		// Note: Stripe does not yet support specifying an existing card for a subscription.
		// This feature is coming in a future relase, however.
		// Currently you can only specify a card token and the same card is used for all
		// customer subscriptions.
		$stripe_subscriptions = $this->stripe_customer->subscriptions;

		if ( ! $stripe_subscriptions) {
			throw new \Exception("Stripe Customer does not exist.");
		}

		$stripe_subscription = $stripe_subscriptions->create(array(
			'plan'      => $plan,
			'quantity'  => Arr::get($properties, 'quantity') ? Arr::get($properties, 'quantity') : null,
			'trial_end' => $trial_end,
			'coupon'    => Arr::get($properties, 'coupon') ? Arr::get($properties, 'coupon') : null,
			'card'      => Arr::get($properties, 'card_token') ? Arr::get($properties, 'card_token') : null,
		));
		
		$this->id = $stripe_subscription->id;
		
		return $this;
	}
	
	/**
	 * Update a subscription.
	 *
	 * @param array $properties
	 * 
	 * @return Subscription
	 */
	public function update(array $properties = array())
	{
		$this->info();
		
		if (!empty($properties['plan'])) {
			$this->stripe_subscription->plan = $properties['plan'];
		}
		if (!empty($properties['quantity'])) {
			$this->stripe_subscription->quantity = $properties['quantity'];
		}
		if (!empty($properties['trial_ends_at'])) {
			if (strtotime($properties['trial_ends_at']) <= time()) {
				$this->stripe_subscription->trial_end = 'now';
			}
			else {
				$this->stripe_subscription->trial_end = strtotime($properties['trial_ends_at']);
			}
		}
		if (isset($properties['prorate'])) {
			$this->stripe_subscription->prorate = $properties['prorate'];
		}
		if (!empty($properties['coupon'])) {
			$this->stripe_subscription->coupon = $properties['coupon'];
		}
		
		if (!empty($properties['card_token'])) {
			$this->stripe_subscription->card = $properties['card_token'];
		}
		else if (!empty($properties['card'])) {
			$this->stripe_subscription->card = $properties['card'];
		}
		
		$this->stripe_subscription->save();
		$this->stripe_subscription = null;
		
		return $this;
	}
	
	/**
	 * Cancel a subscription.
	 *
	 * @param bool $at_period_end
	 * 
	 * @return Subscription
	 */
	public function cancel($at_period_end = true)
	{
		$this->info();
		$this->stripe_subscription->cancel(array('at_period_end' => $at_period_end));
		$this->stripe_subscription = null;
		return $this;
	}
	
	/**
	 * Gets the native subscription response.
	 *
	 * @return Stripe_Customer
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->stripe_subscription;
	}
}
