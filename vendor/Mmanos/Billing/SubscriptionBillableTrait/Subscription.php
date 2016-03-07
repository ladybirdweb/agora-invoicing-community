<?php namespace Mmanos\Billing\SubscriptionBillableTrait;

use Mmanos\Billing\Facades\Billing;
use Illuminate\Support\Arr;
use Exception;

class Subscription
{
	/**
	 * Subscription model.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	
	/**
	 * Charge gateway instance.
	 *
	 * @var \Mmanos\Billing\Gateways\SubscriptionInterface
	 */
	protected $subscription;
	
	/**
	 * Subscription info array.
	 *
	 * @var array
	 */
	protected $info;
	
	/**
	 * Subscription plan.
	 *
	 * @var mixed
	 */
	protected $plan;
	
	/**
	 * The coupon to apply to the subscription.
	 *
	 * @var string
	 */
	protected $coupon;
	
	/**
	 * The credit card token to assign to the subscription.
	 *
	 * @var string
	 */
	protected $card_token;
	
	/**
	 * The credit card id to assign to the subscription.
	 *
	 * @var string
	 */
	protected $card;
	
	/**
	 * Whether or not to force skip the trial period.
	 *
	 * @var bool
	 */
	protected $skip_trial;
	
	/**
	 * Whether or not this subscription should be free (not stored in billing gateway).
	 *
	 * @var bool
	 */
	protected $is_free;
	
	/**
	 * Create a new SubscriptionBillableTrait Subscription instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model            $model
	 * @param \Mmanos\Billing\Gateways\SubscriptionInterface $subscription
	 * @param mixed                                          $plan
	 * @param array                                          $info
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model, \Mmanos\Billing\Gateways\SubscriptionInterface $subscription = null, $plan = null, array $info = null)
	{
		if (null === $plan) {
			$plan = $model->billing_plan;
		}
		
		$this->model = $model;
		$this->plan = $plan;
		$this->subscription = $subscription;
		
		if (!$this->subscription) {
			$this->subscription = $this->model->gatewaySubscription();
		}
		
		if (empty($info) && $this->subscription) {
			try {
				$info = $this->subscription->info();
			} catch (Exception $e) {}
		}
		
		$this->info = $info;
	}
	
	/**
	 * Create this subscription in the billing gateway.
	 *
	 * @param array $properties
	 * 
	 * @return Subscription
	 */
	public function create(array $properties = array())
	{
		if ($this->is_free || $this->canLocalTrial()) {
			return $this->storeLocal(array(
				'quantity' => Arr::get($properties, 'quantity', 1),
			));
		}
		
		if ($this->model->billingIsActive()) {
			return $this;
		}
		
		if ($customer = $this->model->customer()) {
			if (!$customer->readyForBilling()) {
				if ($this->card_token) {
					$customer->billing()->withCardToken($this->card_token)->create($properties);
					if (!empty($customer->billing_cards)) {
						$this->card_token = null;
					}
				}
				else {
					$customer->billing()->create($properties);
				}
			}
			else if ($this->card_token) {
				$this->card = $customer->creditcards()->create($this->card_token)->id;
				$this->card_token = null;
			}
		}
		
		$this->subscription = Billing::subscription(null, $customer ? $customer->gatewayCustomer() : null)
			->create($this->plan, array_merge($properties, array(
				'trial_ends_at' => $this->skip_trial ? date('Y-m-d H:i:s') : $this->model->billing_trial_ends_at,
				'coupon'        => $this->coupon,
				'quantity'      => Arr::get($properties, 'quantity', 1),
				'card_token'    => $this->card_token,
				'card'          => $this->card,
			)));
		
		$this->refresh();
		
		return $this;
	}
	
	/**
	 * Cancel this subscription in the billing gateway.
	 *
	 * @param bool $at_period_end
	 * 
	 * @return Subscription
	 */
	public function cancel($at_period_end = true)
	{
		if (!$this->model->billingIsActive()) {
			return $this->storeLocal(array(
				'subscription_ends_at' => date('Y-m-d H:i:s'),
			));
		}
		
		$this->subscription->cancel($at_period_end);
		
		$this->refresh();
		
		return $this;
	}
	
	/**
	 * Resume a canceled subscription in the billing gateway.
	 *
	 * @param int $quantity
	 * 
	 * @return Subscription
	 */
	public function resume($quantity = null)
	{
		if (null === $quantity) {
			$quantity = $this->model->billing_quantity;
		}
		
		if ($this->is_free || $this->canLocalTrial()) {
			return $this->storeLocal(array(
				'quantity' => $quantity,
			));
		}
		
		if (!$this->model->canceled()) {
			return $this;
		}
		
		if (($customer = $this->model->customer()) && $this->card_token) {
			$this->card = $customer->creditcards()->create($this->card_token)->id;
			$this->card_token = null;
		}
		
		$this->subscription = Billing::subscription($this->model->billing_subscription, $customer ? $customer->gatewayCustomer() : null);
		if ($this->subscription->info()) {
			$this->subscription->update(array(
				'plan'          => $this->plan,
				'trial_ends_at' => $this->skip_trial ? date('Y-m-d H:i:s') : $this->model->billing_trial_ends_at,
				'prorate'       => false,
				'quantity'      => $quantity,
				'card_token'    => $this->card_token,
				'card'          => $this->card,
			));
		}
		else {
			$this->subscription = Billing::subscription(null, $customer ? $customer->gatewayCustomer() : null)
				->create($this->plan, array(
					'trial_ends_at' => $this->skip_trial ? date('Y-m-d H:i:s') : $this->model->billing_trial_ends_at,
					'quantity'      => $quantity,
					'card_token'    => $this->card_token,
					'card'          => $this->card,
				));
		}
		
		$this->refresh();
		
		return $this;
	}
	
	/**
	 * Swap this subscription to a new plan in the billing gateway.
	 *
	 * @param int $quantity
	 * 
	 * @return Subscription
	 */
	public function swap($quantity = null)
	{
		if (null === $quantity) {
			$quantity = $this->model->billing_quantity;
		}
		
		if ($this->is_free || $this->canLocalTrial()) {
			return $this->storeLocal(array(
				'quantity' => $quantity,
			));
		}
		
		if (!$this->model->billingIsActive()) {
			if ($this->model->canceled()) {
				return $this->resume($quantity);
			}
			else {
				return $this->create(array('quantity' => $quantity));
			}
		}
		
		if (($customer = $this->model->customer()) && $this->card_token) {
			$this->card = $customer->creditcards()->create($this->card_token)->id;
			$this->card_token = null;
		}
		
		// If no specific trial end date has been set, the default behavior should be
		// to maintain the current trial state, whether that is "active" or to run
		// the swap out with the current trial period.
		if (!$this->model->billing_trial_ends_at) {
			$this->skipTrial();
		}
		
		$this->subscription->update(array(
			'plan'          => $this->plan,
			'quantity'      => $quantity,
			'trial_ends_at' => $this->skip_trial ? date('Y-m-d H:i:s') : $this->model->billing_trial_ends_at,
			'card_token'    => $this->card_token,
			'card'          => $this->card,
		));
		
		$this->refresh();
		
		return $this;
	}
	
	/**
	 * Increment the quantity of this subscription in the billing gateway.
	 *
	 * @param int $count
	 * 
	 * @return Subscription
	 */
	public function increment($count = 1)
	{
		$quantity = $this->model->billing_quantity + $count;
		
		if ($this->is_free || $this->canLocalTrial()) {
			return $this->storeLocal(array(
				'quantity' => $quantity,
			));
		}
		
		if (!$this->model->billingIsActive()) {
			return $this;
		}
		
		$this->subscription->update(array(
			'plan'          => $this->model->billing_plan,
			'quantity'      => $quantity,
			'trial_ends_at' => $this->skip_trial ? date('Y-m-d H:i:s') : $this->model->billing_trial_ends_at,
		));
		
		$this->refresh();
		
		return $this;
	}
	
	/**
	 * Decrement the quantity of this subscription in the billing gateway.
	 *
	 * @param int $count
	 * 
	 * @return Subscription
	 */
	public function decrement($count = 1)
	{
		$quantity = $this->model->billing_quantity - $count;
		
		if ($this->is_free || $this->canLocalTrial()) {
			return $this->storeLocal(array(
				'quantity' => $quantity,
			));
		}
		
		if (!$this->model->billingIsActive()) {
			return $this;
		}
		
		$this->subscription->update(array(
			'plan'          => $this->model->billing_plan,
			'quantity'      => $quantity,
			'trial_ends_at' => $this->skip_trial ? date('Y-m-d H:i:s') : $this->model->billing_trial_ends_at,
		));
		
		$this->refresh();
		
		return $this;
	}
	
	/**
	 * Refresh local model data for this subscription.
	 *
	 * @return Subscription
	 */
	public function refresh()
	{
		$info = array();
		if ($this->subscription) {
			try {
				$info = $this->subscription->info();
			} catch (Exception $e) {}
		}
		
		if ($info) {
			$this->model->billing_active = 1;
			$this->model->billing_subscription = $this->subscription->id();
			$this->model->billing_free = 0;
			$this->model->billing_plan = Arr::get($info, 'plan');
			$this->model->billing_amount = Arr::get($info, 'amount', 0);
			$this->model->billing_interval = Arr::get($info, 'interval');
			$this->model->billing_quantity = Arr::get($info, 'quantity');
			$this->model->billing_card = Arr::get($info, 'card');
			$this->model->billing_trial_ends_at = Arr::get($info, 'trial_ends_at');
			$this->model->billing_subscription_ends_at = null;
			$this->model->billing_subscription_discounts = Arr::get($info, 'discounts');
			
			if (!Arr::get($info, 'active')) {
				$this->model->billing_active = 0;
				$this->model->billing_trial_ends_at = null;
				$this->model->billing_subscription_ends_at = Arr::get($info, 'period_ends_at', date('Y-m-d H:i:s'));
			}
		}
		else {
			$this->model->billing_active = 0;
			$this->model->billing_subscription = null;
			$this->model->billing_free = 0;
			$this->model->billing_plan = null;
			$this->model->billing_amount = 0;
			$this->model->billing_interval = null;
			$this->model->billing_quantity = 0;
			$this->model->billing_card = null;
			$this->model->billing_trial_ends_at = null;
			$this->model->billing_subscription_ends_at = null;
			$this->model->billing_subscription_discounts = null;
		}
		
		$this->model->save();
		
		$this->info = $info;
		
		return $this;
	}
	
	/**
	 * Store the subscription data locally (not in billing gateway).
	 *
	 * @param array $properties
	 * 
	 * @return Subscription
	 */
	public function storeLocal(array $properties = array())
	{
		// Cancel in billing gateway, if active.
		if ($this->model->billingIsActive()) {
			$trial_ends_at = $this->model->billing_trial_ends_at;
			$this->subscription->cancel(true);
			$this->refresh();
			$this->model->billing_trial_ends_at = $trial_ends_at;
			$this->model->billing_subscription_ends_at = null;
		}
		
		$this->model->billing_active = 0;
		$this->model->billing_amount = 0;
		$this->model->billing_interval = null;
		$this->model->billing_card = null;
		$this->model->billing_subscription_discounts = null;
		
		$this->model->billing_free = (int) $this->is_free;
		
		if ($this->plan) {
			$this->model->billing_plan = $this->plan;
		}
		
		if (!empty($properties['quantity'])) {
			$this->model->billing_quantity = $properties['quantity'];
		}
		
		$this->model->billing_trial_ends_at = $this->skip_trial ? date('Y-m-d H:i:s') : $this->model->billing_trial_ends_at;
		
		if (!empty($properties['subscription_ends_at'])) {
			$this->model->billing_subscription_ends_at = $properties['subscription_ends_at'];
			$this->model->billing_trial_ends_at = null;
		}
		
		$this->model->save();
		
		return $this;
	}
	
	/**
	 * Whether or not a local trial is allowed for this subscription.
	 *
	 * @return bool
	 */
	public function canLocalTrial()
	{
		if (!$this->model->requiresCardUpFront()
			&& $this->model->onTrial()
			&& !$this->card_token
			&& !(
				($customer = $this->model->customer())
				&& $customer->readyForBilling()
				&& !empty($customer->billing_cards)
			)
		) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * The coupon to apply to a new subscription.
	 *
	 * @param string $coupon
	 * 
	 * @return Subscription
	 */
	public function withCoupon($coupon)
	{
		$this->coupon = $coupon;
		return $this;
	}
	
	/**
	 * The credit card token to assign to a new subscription.
	 *
	 * @param string $card_token
	 * 
	 * @return Subscription
	 */
	public function withCardToken($card_token)
	{
		$this->card_token = $card_token;
		return $this;
	}
	
	/**
	 * The credit card id or array to assign to a new subscription.
	 *
	 * @param string|array $card
	 * 
	 * @return Subscription
	 */
	public function withCard($card)
	{
		$this->card = is_array($card) ? Arr::get($card, 'id') : $card;
		return $this;
	}
	
	/**
	 * Indicate that no trial should be enforced on the operation.
	 *
	 * @return Subscription
	 */
	public function skipTrial()
	{
		$this->skip_trial = true;
		return $this;
	}
	
	/**
	 * Indicate that this subscription should be free and not stored in the billing gateway.
	 *
	 * @return Subscription
	 */
	public function isFree()
	{
		$this->is_free = true;
		return $this;
	}
	
	/**
	 * Return the Eloquent model associated with this helper object.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function model()
	{
		return $this->model;
	}
	
	/**
	 * Convert this instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->info;
	}
	
	/**
	 * Dynamically check a values existence from the subscription.
	 *
	 * @param string $key
	 * 
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->info[$key]);
	}
	
	/**
	 * Dynamically get values from the subscription.
	 *
	 * @param string $key
	 * 
	 * @return mixed
	 */
	public function __get($key)
	{
		return isset($this->info[$key]) ? $this->info[$key] : null;
	}
}
