<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Mmanos\Billing\Facades\Billing;
use Illuminate\Support\Arr;

class Subscriptions
{
	/**
	 * Customer model.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	
	/**
	 * Query limit.
	 *
	 * @var int
	 */
	protected $limit;
	
	/**
	 * Query offset.
	 *
	 * @var int
	 */
	protected $offset;
	
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
	 * Create a new CustomerBillableTrait Subscriptions instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @param mixed                               $plan
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model, $plan = null)
	{
		$this->model = $model;
		$this->plan = $plan;
	}
	
	/**
	 * Fetch the credit subscription.
	 *
	 * @return array
	 */
	public function get()
	{
		$subscriptions = array();
		
		foreach ($this->model->subscriptionModelsArray() as $subscription) {
			$subscriptions[] = new \Mmanos\Billing\SubscriptionBillableTrait\Subscription(
				$subscription,
				$subscription->gatewaySubscription(),
				null,
				array(
					'id'                => $subscription->billing_subscription,
					'plan'              => $subscription->billing_plan,
					'amount'            => $subscription->billing_amount,
					'interval'          => $subscription->billing_interval,
					'active'            => $subscription->billing_active,
					'quantity'          => $subscription->billing_quantity,
					'started_at'        => null,
					'period_started_at' => null,
					'period_ends_at'    => null,
					'trial_ends_at'     => $subscription->billing_trial_ends_at,
					'card'              => $subscription->billing_card,
					'discounts'         => $subscription->billing_subscription_discounts,
				)
			);
		}
		
		return $subscriptions;
	}
	
	/**
	 * Find and return the first credit card.
	 *
	 * @return \Mmanos\Billing\SubscriptionBillableTrait\Subscription
	 */
	public function first()
	{
		return Arr::get($this->get(), 0);
	}
	
	/**
	 * Find and return a credit card.
	 *
	 * @param mixed $id
	 * 
	 * @return \Mmanos\Billing\SubscriptionBillableTrait\Subscription
	 */
	public function find($id)
	{
		foreach ($this->get() as $subscription) {
			if ($subscription->id == $id) {
				return $subscription;
			}
		}
		
		return null;
	}
	
	/**
	 * Create this subscription in the billing gateway.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @param array                               $properties
	 * 
	 * @return \Mmanos\Billing\SubscriptionBillableTrait\Subscription
	 */
	public function create(\Illuminate\Database\Eloquent\Model $model, array $properties = array())
	{
		$subscription = $model->subscription($this->plan);
		
		if ($this->card_token) {
			$subscription->withCardToken($this->card_token);
		}
		if ($this->card) {
			$subscription->withCard($this->card);
		}
		if ($this->coupon) {
			$subscription->withCoupon($this->coupon);
		}
		if ($this->skip_trial) {
			$subscription->skipTrial();
		}
		if ($this->is_free) {
			$subscription->isFree();
		}
		
		return $subscription->create($properties);
	}
	
	/**
	 * The coupon to apply to a new subscription.
	 *
	 * @param string $coupon
	 * 
	 * @return Subscriptions
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
	 * @return Subscriptions
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
	 * @return Subscriptions
	 */
	public function withCard($card)
	{
		$this->card = is_array($card) ? Arr::get($card, 'id') : $card;
		return $this;
	}
	
	/**
	 * Indicate that no trial should be enforced on the operation.
	 *
	 * @return Subscriptions
	 */
	public function skipTrial()
	{
		$this->skip_trial = true;
		return $this;
	}
	
	/**
	 * Indicate that this subscription should be free and not stored in the billing gateway.
	 *
	 * @return Subscriptions
	 */
	public function isFree()
	{
		$this->is_free = true;
		return $this;
	}
}
