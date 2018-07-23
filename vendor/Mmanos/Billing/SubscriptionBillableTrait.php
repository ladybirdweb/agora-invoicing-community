<?php namespace Mmanos\Billing;

use Mmanos\Billing\Facades\Billing;
use LogicException;

trait SubscriptionBillableTrait
{
	/**
	 * Return the gateway subscription object for this model.
	 *
	 * @return Mmanos\Billing\Gateways\SubscriptionInterface
	 */
	public function gatewaySubscription()
	{
		if (!$this->everSubscribed()) {
			return null;
		}
		
		if ($customer = $this->customer()) {
			$customer = $customer->gatewayCustomer();
		}
		
		return Billing::subscription($this->billing_subscription, $customer);
	}
	
	/**
	 * Return the subscription billing helper object.
	 *
	 * @param mixed $plan
	 * 
	 * @return SubscriptionBillableTrait\Subscription
	 */
	public function subscription($plan = null)
	{
		return new SubscriptionBillableTrait\Subscription($this, $this->gatewaySubscription(), $plan);
	}
	
	/**
	 * Determine if the entity is within their trial period.
	 *
	 * @return bool
	 */
	public function onTrial()
	{
		if ($this->billing_trial_ends_at) {
			return time() < strtotime($this->billing_trial_ends_at);
		}
		
		return false;
	}
	
	/**
	 * Determine if the entity is on grace period after cancellation.
	 *
	 * @return bool
	 */
	public function onGracePeriod()
	{
		if ($this->billing_subscription_ends_at) {
			return time() < strtotime($this->billing_subscription_ends_at);
		}
		
		return false;
	}
	
	/**
	 * Determine if the entity has an active subscription.
	 *
	 * @return bool
	 */
	public function subscribed()
	{
		if ($this->billing_free) {
			if (!$this->billing_subscription_ends_at
				|| time() < strtotime($this->billing_subscription_ends_at)
			) {
				return true;
			}
		}
		
		if (!isset($this->cardUpFront) || $this->cardUpFront) {
			return $this->billingIsActive() || $this->onGracePeriod();
		}
		
		return $this->billingIsActive() || $this->onGracePeriod() || $this->onTrial();
	}
	
	/**
	 * Determine if the entity's trial has expired.
	 *
	 * @return bool
	 */
	public function expired()
	{
		return !$this->subscribed() && $this->billing_trial_ends_at && strtotime($this->billing_trial_ends_at) <= time();
	}
	
	/**
	 * Determine if the entity had a subscription which is no longer active.
	 *
	 * @return bool
	 */
	public function canceled()
	{
		return $this->everSubscribed() && !$this->billingIsActive();
	}
	
	/**
	 * Deteremine if the user has ever been subscribed.
	 *
	 * @return bool
	 */
	public function everSubscribed()
	{
		return !empty($this->billing_subscription);
	}
	
	/**
	 * Determine if the entity has a current subscription.
	 *
	 * @return bool
	 */
	public function billingIsActive()
	{
		return $this->billing_active;
	}
	
	/**
	 * Whether or not this model requires a credit card up front.
	 *
	 * @return bool
	 */
	public function requiresCardUpFront()
	{
		if (!isset($this->cardUpFront) || $this->cardUpFront) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Return the Eloquent model acting as the billing customer for this model.
	 * The customer model can be defined in one of the following ways:
	 * - A 'customermodel' relationship on this model.
	 * - A 'customermodel' method on this model that returns a valid Eloquent model.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function customer()
	{
		// Note: Laravel throws a LogicException if a customer method exists but
		// doesn't return a valid relationship.
		try {
			if ($customer = $this->customermodel) {
				return $customer;
			}
		} catch (LogicException $e) {}
		
		if (method_exists($this, 'customermodel')) {
			return $this->customermodel();
		}
		
		// Check for customer/subscription on the same model.
		if (method_exists($this, 'gatewayCustomer')) {
			return $this;
		}
		
		return null;
	}
	
	/**
	 * Getter for billing_subscription_discounts property.
	 *
	 * @param string $value
	 * 
	 * @return array
	 */
	public function getBillingSubscriptionDiscountsAttribute($value)
	{
		return $value ? json_decode($value, true) : array();
	}
	
	/**
	 * Setter for billing_subscription_discounts property.
	 *
	 * @param array $value
	 * 
	 * @return void
	 */
	public function setBillingSubscriptionDiscountsAttribute($value)
	{
		$this->attributes['billing_subscription_discounts'] = empty($value) ? null : json_encode($value);
	}
	
	/**
	 * Register a billingActivated model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function billingActivated($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('billingActivated', $callback);
	}
	
	/**
	 * Register a billingCanceled model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function billingCanceled($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('billingCanceled', $callback);
	}
	
	/**
	 * Register a planSwapped model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function planSwapped($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('planSwapped', $callback);
	}
	
	/**
	 * Register a planChanged model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function planChanged($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('planChanged', $callback);
	}
	
	/**
	 * Register a subscriptionIncremented model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function subscriptionIncremented($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('subscriptionIncremented', $callback);
	}
	
	/**
	 * Register a subscriptionDecremented model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function subscriptionDecremented($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('subscriptionDecremented', $callback);
	}
	
	/**
	 * Register a billingResumed model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function billingResumed($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('billingResumed', $callback);
	}
	
	/**
	 * Register a trialExtended model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function trialExtended($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('trialExtended', $callback);
	}
	
	/**
	 * Register a trialChanged model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function trialChanged($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('trialChanged', $callback);
	}
	
	/**
	 * Register a trialWillEnd model event with the dispatcher.
	 * Triggered via webhook.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function trialWillEnd($callback)
	{
		static::registerModelEvent('trialWillEnd', $callback);
	}
	
	/**
	 * Register a subscriptionDiscountAdded model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function subscriptionDiscountAdded($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('subscriptionDiscountAdded', $callback);
	}
	
	/**
	 * Register a subscriptionDiscountRemoved model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function subscriptionDiscountRemoved($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('subscriptionDiscountRemoved', $callback);
	}
	
	/**
	 * Register a subscriptionDiscountUpdated model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function subscriptionDiscountUpdated($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('subscriptionDiscountUpdated', $callback);
	}
	
	/**
	 * Register a subscriptionDiscountChanged model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function subscriptionDiscountChanged($callback)
	{
		static::listenForSubscriptionEvents();
		static::registerModelEvent('subscriptionDiscountChanged', $callback);
	}
	
	/**
	 * Fire the given event for the model.
	 *
	 * @param string $event
	 * 
	 * @return mixed
	 */
	public function fireSubscriptionEvent($event)
	{
		if ( ! isset(static::$dispatcher)) return true;
		
		// We will append the names of the class to the event to distinguish it from
		// other model events that are fired, allowing us to listen on each model
		// event set individually instead of catching event for all the models.
		$event = "eloquent.{$event}: ".get_class($this);
		
		$args = array_merge(array($this), array_slice(func_get_args(), 1));
		
		return static::$dispatcher->fire($event, $args);
	}
	
	/**
	 * Register listeners for model change events so we can trigger our own
	 * custom events.
	 *
	 * @return void
	 */
	protected static function listenForSubscriptionEvents()
	{
		static $listening_for_subscription_events;
		if ($listening_for_subscription_events) {
			return;
		}
		
		static::saved(function ($model) {
			$original = $model->getOriginal();
			
			if ($model->isDirty('billing_active')) {
				if (empty($original['billing_active']) && !empty($model->billing_active)) {
					$model->fireSubscriptionEvent('billingActivated');
					
					if ($model->isDirty('billing_subscription_ends_at')) {
						if (empty($model->billing_subscription_ends_at) && !empty($original['billing_subscription_ends_at'])) {
							$model->fireSubscriptionEvent('billingResumed');
						}
					}
				}
				else if (empty($model->billing_active) && !empty($original['billing_active'])) {
					$model->fireSubscriptionEvent('billingCanceled');
				}
			}
			if ($model->isDirty('billing_plan')) {
				if (!empty($original['billing_plan']) && !empty($model->billing_plan)) {
					$model->fireSubscriptionEvent('planSwapped');
				}
				if (!empty($model->billing_plan)) {
					$model->fireSubscriptionEvent('planChanged');
				}
			}
			if ($model->isDirty('billing_quantity')) {
				if ($model->billing_quantity > 0 && $model->getOriginal('billing_quantity') > 0) {
					if ($model->billing_quantity > $model->getOriginal('billing_quantity')) {
						$model->fireSubscriptionEvent('subscriptionIncremented');
					}
					else {
						$model->fireSubscriptionEvent('subscriptionDecremented');
					}
				}
			}
			if ($model->isDirty('billing_trial_ends_at')) {
				if (!empty($model->billing_trial_ends_at) && !empty($original['billing_trial_ends_at'])) {
					if (strtotime($model->billing_trial_ends_at) > strtotime($model->getOriginal('billing_trial_ends_at'))) {
						$model->fireSubscriptionEvent('trialExtended');
					}
				}
				$model->fireSubscriptionEvent('trialChanged');
			}
			if ($model->isDirty('billing_subscription_discounts')) {
				if (count($model->billing_subscription_discounts) > count(json_decode($model->getOriginal('billing_subscription_discounts'), true))) {
					$model->fireSubscriptionEvent('subscriptionDiscountAdded');
				}
				else if (count($model->billing_subscription_discounts) < count(json_decode($model->getOriginal('billing_subscription_discounts'), true))) {
					$model->fireSubscriptionEvent('subscriptionDiscountRemoved');
				}
				else if (!empty($model->billing_subscription_discounts)) {
					$model->fireSubscriptionEvent('subscriptionDiscountUpdated');
				}
				$model->fireSubscriptionEvent('subscriptionDiscountChanged');
			}
		});
		
		$listening_for_subscription_events = true;
	}
}
