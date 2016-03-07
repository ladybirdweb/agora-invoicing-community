<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;
use Exception;

class Billing
{
	/**
	 * Customer model.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	
	/**
	 * Customer gateway instance.
	 *
	 * @var \Mmanos\Billing\Gateways\CustomerInterface
	 */
	protected $customer;
	
	/**
	 * Customer info array.
	 *
	 * @var array
	 */
	protected $info = array();
	
	/**
	 * The coupon to apply to the customer.
	 *
	 * @var string
	 */
	protected $coupon;
	
	/**
	 * The credit card token to assign to the customer.
	 *
	 * @var string
	 */
	protected $card_token;
	
	/**
	 * Whether or not to create all pending subscriptions after this operation.
	 *
	 * @var bool
	 */
	protected $with_subscriptions;
	
	/**
	 * Create a new CustomerBillableTrait Billing instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model)
	{
		$this->model = $model;
		$this->customer = $this->model->gatewayCustomer();
		$this->info = $this->customer ? $this->customer->info() : array();
	}
	
	/**
	 * Create this customer in the billing gateway.
	 *
	 * @param array $properties
	 * 
	 * @return Billing
	 */
	public function create(array $properties = array())
	{
		if ($this->model->readyForBilling()) {
			return $this;
		}
		
		$this->customer = \Mmanos\Billing\Facades\Billing::customer()->create(array_merge($properties, array(
			'coupon'     => $this->coupon,
			'card_token' => $this->card_token,
		)));
		
		$this->refresh();
		
		if ($this->with_subscriptions) {
			$this->createPendingSubscriptions();
		}
		
		return $this;
	}
	
	/**
	 * Update this customer in the billing gateway.
	 *
	 * @param array $properties
	 * 
	 * @return Billing
	 */
	public function update(array $properties = array())
	{
		if (!$this->model->readyForBilling()) {
			return $this;
		}
		
		$this->customer->update(array_merge($properties, array(
			'coupon'     => $this->coupon,
			'card_token' => $this->card_token,
		)));
		
		$this->refresh();
		
		// If card changed, refresh all subscription records that reference an invalid card.
		if ($this->card_token && ($subscriptions = $this->model->subscriptionModelsArray())) {
			$cards = $this->model->billing_cards;
			foreach ($subscriptions as $subscription) {
				if (!$subscription->billingIsActive()) {
					continue;
				}
				
				$valid_card = false;
				if (!empty($subscription->billing_card) && !empty($cards)) {
					foreach ($cards as $card) {
						if ($subscription->billing_card == Arr::get($card, 'id')) {
							$valid_card = true;
							break;
						}
					}
				}
				
				if (!$valid_card) {
					$subscription->subscription()->refresh();
				}
			}
		}
		
		if ($this->with_subscriptions) {
			$this->createPendingSubscriptions();
		}
		
		return $this;
	}
	
	/**
	 * Delete this customer in the billing gateway.
	 *
	 * @param array $properties
	 * 
	 * @return Billing
	 */
	public function delete(array $properties = array())
	{
		if (!$this->model->readyForBilling()) {
			return $this;
		}
		
		$this->customer->delete();
		
		$this->refresh();
		
		// Deactivate all customer subscriptions.
		if ($subscriptions = $this->model->subscriptionModelsArray()) {
			foreach ($subscriptions as $subscription) {
				$subscription->billing_subscription_ends_at = $subscription->billingIsActive() ? date('Y-m-d H:i:s') : null;
				$subscription->billing_active = 0;
				$subscription->billing_subscription = null;
				$subscription->billing_trial_ends_at = null;
				$subscription->save();
			}
		}
		
		return $this;
	}
	
	/**
	 * Refresh local model data for this customer.
	 *
	 * @return Billing
	 */
	public function refresh()
	{
		$info = array();
		if ($this->customer) {
			try {
				$info = $this->customer->info();
			} catch (Exception $e) {}
		}
		
		if (!empty($info)) {
			$this->model->billing_id = $this->customer->id();
			$this->model->billing_discounts = Arr::get($info, 'discounts');
			
			$cards = array();
			foreach ($this->customer->cards() as $card) {
				$cards[] = $card->info();
			}
			$this->model->billing_cards = $cards;
		}
		else {
			$this->model->billing_id = null;
			$this->model->billing_cards = null;
			$this->model->billing_discounts = null;
		}
		
		$this->model->save();
		
		$this->info = $info;
		
		return $this;
	}
	
	/**
	 * Create all pending (usually trialing) customer subscriptions in the billing gateway.
	 * To activate, the subscription must:
	 * - Not already be active
	 * - Not be free
	 * - Not require a credit card up front
	 * - Already have a plan selected
	 *
	 * @return Billing
	 */
	public function createPendingSubscriptions()
	{
		foreach ($this->model->subscriptionModelsArray() as $model) {
			if ($model->billingIsActive()) {
				continue;
			}
			if ($model->billing_free) {
				continue;
			}
			if ($model->requiresCardUpFront()) {
				continue;
			}
			if (!$model->billing_plan) {
				continue;
			}
			
			$model->subscription($model->billing_plan)->create();
		}
		
		return $this;
	}
	
	/**
	 * The coupon to apply to a new customer.
	 *
	 * @param string $coupon
	 * 
	 * @return Billing
	 */
	public function withCoupon($coupon)
	{
		$this->coupon = $coupon;
		return $this;
	}
	
	/**
	 * The credit card token to assign to a new customer.
	 *
	 * @param string $card_token
	 * 
	 * @return Billing
	 */
	public function withCardToken($card_token)
	{
		$this->card_token = $card_token;
		return $this;
	}
	
	/**
	 * Create all subscriptions in the billing gateway after this action.
	 * Useful if subscription trials have already been started but no
	 * billing customer was created yet (cardUpFront = false).
	 *
	 * @return Billing
	 */
	public function withSubscriptions()
	{
		$this->with_subscriptions = true;
		return $this;
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
	 * Dynamically check a values existence from the customer.
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
	 * Dynamically get values from the customer.
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
