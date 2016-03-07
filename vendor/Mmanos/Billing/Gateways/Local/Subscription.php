<?php namespace Mmanos\Billing\Gateways\Local;

use Mmanos\Billing\Gateways\SubscriptionInterface;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class Subscription implements SubscriptionInterface
{
	/**
	 * The gateway instance.
	 *
	 * @var Gateway
	 */
	protected $gateway;
	
	/**
	 * Local customer object.
	 *
	 * @var Models\Customer
	 */
	protected $local_customer;
	
	/**
	 * Primary identifier.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Local subscription object.
	 *
	 * @var Models\Subscription
	 */
	protected $local_subscription;
	
	/**
	 * Create a new Local subscription instance.
	 *
	 * @param Gateway         $gateway
	 * @param Models\Customer $customer
	 * @param mixed           $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, Models\Customer $customer = null, $id = null)
	{
		$this->gateway = $gateway;
		$this->local_customer = $customer;
		
		if ($id instanceof Models\Subscription) {
			$this->local_subscription = $id;
			$this->id = $this->local_subscription->id;
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
		if (!$this->id || !$this->local_customer) {
			return null;
		}
		
		if (!$this->local_subscription) {
			$this->local_subscription = $this->local_customer->subscriptions()->where('id', $this->id)->first();
			$this->gateway->apiDelay();
			
			if ($this->local_subscription) {
				$this->local_subscription->process();
			}
		}
		
		if (!$this->local_subscription) {
			return null;
		}
		
		if ($this->local_subscription->gracePeriodEnded()) {
			return $this->local_subscription = null;
		}
		
		$discounts = array();
		if ($this->local_subscription->coupon) {
			$started_at = $this->local_subscription->subscription->created_at;
			
			$ends_at = null;
			if ($this->local_subscription->coupon->duration_in_months) {
				$ends_at = $started_at->copy()->addMonths(
					$this->local_subscription->coupon->duration_in_months
				);
			}
			
			$discounts[] = array(
				'coupon'      => $this->local_subscription->coupon->code,
				'amount_off'  => $this->local_subscription->coupon->amount_off,
				'percent_off' => $this->local_subscription->coupon->percent_off,
				'started_at'  => (string) $started_at,
				'ends_at'     => $ends_at ? (string) $ends_at : null,
			);
		}
		
		return array(
			'id'                => $this->id,
			'plan'              => $this->local_subscription->plan->key,
			'amount'            => $this->local_subscription->plan->amount,
			'interval'          => $this->local_subscription->plan->interval,
			'active'            => $this->local_subscription->cancel_at ? false : true,
			'quantity'          => $this->local_subscription->quantity,
			'started_at'        => (string) $this->local_subscription->created_at,
			'period_started_at' => (string) $this->local_subscription->period_started_at,
			'period_ends_at'    => (string) $this->local_subscription->period_ends_at,
			'trial_ends_at'     => $this->local_subscription->trial_ends_at ? (string) $this->local_subscription->trial_ends_at : null,
			'card'              => $this->local_subscription->card_id,
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
		$card_id = empty($properties['card']) ? null : $properties['card'];
		if (!empty($properties['card_token'])) {
			$card_id = $this->gateway->customer($this->local_customer)
				->card()
				->create($properties['card_token'])
				->id();
		}
		if (!$card_id) {
			$card_id = $this->local_customer->cards->first()->id;
		}
		
		$coupon_id = null;
		if ($coupon = Arr::get($properties, 'coupon')) {
			$coupon_id = Models\Coupon::where('code', $coupon)->first()->id;
		}
		
		$plan = Models\Plan::where('key', $plan)->first();
		
		$trial_ends_at = null;
		if (!empty($properties['trial_ends_at'])) {
			$trial_ends_at = date('Y-m-d H:i:s', strtotime((string) $properties['trial_ends_at']));
			if (strtotime($trial_ends_at) <= time()) {
				$trial_ends_at = date('Y-m-d H:i:s');
			}
		}
		else if ($plan->trial_period_days) {
			$trial_ends_at = (string) Carbon::now()->addDays($plan->trial_period_days);
		}
		
		$this->local_subscription = Models\Subscription::create(array(
			'customer_id'   => $this->local_customer->id,
			'plan_id'       => $plan->id,
			'card_id'       => Models\Card::find($card_id)->id,
			'coupon_id'     => $coupon_id,
			'quantity'      => Arr::get($properties, 'quantity', 1),
			'trial_ends_at' => $trial_ends_at,
			'cancel_at'     => null,
		));
		
		$this->gateway->apiDelay();
		
		$this->id = $this->local_subscription->id;
		
		$this->local_subscription->process();
		
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
			$this->local_subscription->plan_id = Models\Plan::where('key', $properties['plan'])->first()->id;
		}
		if (!empty($properties['quantity'])) {
			$this->local_subscription->quantity = $properties['quantity'];
		}
		if (!empty($properties['trial_ends_at'])) {
			$trial_ends_at = date('Y-m-d H:i:s', strtotime((string) $properties['trial_ends_at']));
			if (strtotime($properties['trial_ends_at']) <= time()) {
				if ($this->local_subscription->trial_ends_at
					&& (strtotime((string) $this->local_subscription->trial_ends_at) > time())
				) {
					$this->local_subscription->trial_ends_at = date('Y-m-d H:i:s');
				}
			}
			else {
				$this->local_subscription->trial_ends_at = $trial_ends_at;
			}
		}
		if (!empty($properties['coupon'])) {
			$this->local_subscription->coupon_id = $properties['coupon'];
		}
		
		if (!empty($properties['card_token'])) {
			$card_id = $this->gateway->customer($this->local_customer)
				->card()
				->create($properties['card_token'])
				->id();
			
			$this->local_subscription->card_id = Models\Card::find($card_id)->id;
		}
		else if (!empty($properties['card'])) {
			$this->local_subscription->card_id = Models\Card::find($properties['card'])->id;
		}
		
		$this->local_subscription->cancel_at = null;
		
		$this->local_subscription->save();
		$this->gateway->apiDelay();
		$this->local_subscription = null;
		
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
		
		if ($at_period_end) {
			$this->local_subscription->cancel_at = $this->local_subscription->period_ends_at;
			$this->local_subscription->save();
		}
		else {
			$this->local_subscription->delete();
			$this->local_subscription = null;
		}
		
		$this->gateway->apiDelay();
		
		return $this;
	}
	
	/**
	 * Gets the native subscription response.
	 *
	 * @return Models\Subscription
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->local_subscription;
	}
}
