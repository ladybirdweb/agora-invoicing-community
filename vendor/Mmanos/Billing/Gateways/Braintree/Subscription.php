<?php namespace Mmanos\Billing\Gateways\Braintree;

use Mmanos\Billing\Gateways\SubscriptionInterface;
use Illuminate\Support\Arr;
use Braintree_Customer;
use Braintree_Subscription;
use Braintree_Plan;

class Subscription implements SubscriptionInterface
{
	/**
	 * The gateway instance.
	 *
	 * @var Gateway
	 */
	protected $gateway;
	
	/**
	 * Braintree customer object.
	 *
	 * @var Braintree_Customer
	 */
	protected $braintree_customer;
	
	/**
	 * Primary identifier.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * Braintree subscription object.
	 *
	 * @var Braintree_Subscription
	 */
	protected $braintree_subscription;
	
	/**
	 * Create a new Braintree subscription instance.
	 *
	 * @param Gateway         $gateway
	 * @param Braintree_Customer $customer
	 * @param mixed           $id
	 * 
	 * @return void
	 */
	public function __construct(Gateway $gateway, Braintree_Customer $customer = null, $id = null)
	{
		$this->gateway = $gateway;
		$this->braintree_customer = $customer;
		
		if ($id instanceof Braintree_Subscription) {
			$this->braintree_subscription = $id;
			$this->id = $this->braintree_subscription->id;
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
		if (!$this->id) {
			return null;
		}
		
		if (!$this->braintree_subscription) {
			$this->braintree_subscription = Braintree_Subscription::find($this->id);
		}
		
		if (!$this->braintree_subscription) {
			return null;
		}
		
		$trial_ends_at = null;
		if ($this->braintree_subscription->trialPeriod) {
			$created_at = clone $this->braintree_subscription->createdAt;
			$trial_ends_at = date('Y-m-d H:i:s', $created_at->add(
				date_interval_create_from_date_string(
					$this->braintree_subscription->trialDuration . ' '
					. $this->braintree_subscription->trialDurationUnit . 's'
				)
			)->getTimestamp());
		}
		
		$period_started_at = date('Y-m-d H:i:s', $this->braintree_subscription->createdAt->getTimestamp());
		if ($this->braintree_subscription->billingPeriodStartDate) {
			$period_started_at = date('Y-m-d H:i:s', $this->braintree_subscription->billingPeriodStartDate->getTimestamp());
		}
		$period_ends_at = $trial_ends_at;
		if ($this->braintree_subscription->billingPeriodEndDate) {
			$period_ends_at = date('Y-m-d H:i:s', $this->braintree_subscription->billingPeriodEndDate->getTimestamp());
		}
		
		$interval = 1;
		foreach (Braintree_Plan::all() as $plan) {
			if ($plan->id == $this->braintree_subscription->planId) {
				$interval = $plan->billingFrequency;
				break;
			}
		}
		
		$discounts = array();
		foreach ($this->braintree_subscription->discounts as $discount) {
			$started_at = date('Y-m-d H:i:s', $this->braintree_subscription->firstBillingDate->getTimestamp());
			$ends_at = null;
			if (!$discount->neverExpires) {
				$cycle = $interval * 60 * 60 * 24 * 30;
				$ends_at = date('Y-m-d H:i:s', strtotime($started_at) + ($cycle * $discount->numberOfBillingCycles));
			}
			
			$discounts[] = array(
				'coupon'      => $discount->id,
				'amount_off'  => ((float) $discount->amount * 100),
				'percent_off' => null,
				'started_at'  => $started_at,
				'ends_at'     => $ends_at,
			);
		}
		
		return array(
			'id'                => $this->id,
			'plan'              => $this->braintree_subscription->planId,
			'amount'            => ((float) $this->braintree_subscription->price * 100),
			'interval'          => ($interval == 1) ? 'month' : (($interval == 3) ? 'quarter' : 'year'),
			'active'            => ('Canceled' != $this->braintree_subscription->status),
			'quantity'          => 1,
			'started_at'        => date('Y-m-d H:i:s', $this->braintree_subscription->createdAt->getTimestamp()),
			'period_started_at' => $period_started_at,
			'period_ends_at'    => $period_ends_at,
			'trial_ends_at'     => $trial_ends_at,
			'card'              => $this->braintree_subscription->paymentMethodToken,
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
		if (!$token = Arr::get($properties, 'card')) {
			$cards = $this->braintree_customer->creditCards;
			$token = $cards[0]->token;
		}
		
		$props = array(
			'paymentMethodToken' => $token,
			'planId'             => $plan,
		);
		
		if (!empty($properties['coupon'])) {
			$props['discounts']['add'][] = array('inheritedFromId' => $properties['coupon']);
		}
		
		if (!empty($properties['trial_ends_at'])) {
			$now = time();
			$tends = strtotime($properties['trial_ends_at']);
			if ($tends < $now) {
				$props['trialDuration'] = 0;
				$props['trialDurationUnit'] = 'day';
			}
			else if ($tends - $now < (60*60*24*30)) {
				$props['trialDuration'] = round(($tends - $now) / (60*60*24));
				$props['trialDurationUnit'] = 'day';
			}
			else {
				$props['trialDuration'] = round(($tends - $now) / (60*60*24*30));
				$props['trialDurationUnit'] = 'month';
			}
		}
		
		$braintree_subscription = Braintree_Subscription::create($props)->subscription;
		
		$this->id = $braintree_subscription->id;
		$this->braintree_subscription = null;
		
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
		$info = $this->info();
		
		// Braintree won't let you reactivate a canceled subscription. So create a new one.
		if ('Canceled' == $this->braintree_subscription->status) {
			$trial_ends_at = null;
			if ($this->braintree_subscription->trialPeriod) {
				$created_at = clone $this->braintree_subscription->createdAt;
				$trial_ends_at = date('Y-m-d H:i:s', $created_at->add(
					date_interval_create_from_date_string(
						$this->braintree_subscription->trialDuration . ' '
						. $this->braintree_subscription->trialDurationUnit . 's'
					)
				)->getTimestamp());
			}
			
			return $this->create(
				Arr::get($properties, 'plan', $this->braintree_subscription->planId),
				array_merge(array(
					'card'          => $this->braintree_subscription->paymentMethodToken,
					'trial_ends_at' => $trial_ends_at,
				), $properties)
			);
		}
		
		// Braintree won't let you update the trial period.
		// So if we want to cancel an existing trial period, delete the subscription and recreate it.
		if ($info['trial_ends_at']
			&& strtotime($info['trial_ends_at']) > time()
			&& !empty($properties['trial_ends_at'])
			&& strtotime($properties['trial_ends_at']) <= time()
		) {
			$plan = Arr::get($properties, 'plan', $this->braintree_subscription->planId);
			$props = array_merge(array(
				'card'          => $this->braintree_subscription->paymentMethodToken,
				'trial_ends_at' => $properties['trial_ends_at'],
			), $properties);
			
			$this->cancel();
			$this->braintree_subscription = null;
			$this->id = null;
			
			return $this->create($plan, $props);
		}
		
		$props = array();
		
		if (!empty($properties['plan'])) {
			$props['planId'] = $properties['plan'];
			
			foreach (Braintree_Plan::all() as $plan) {
				if ($plan->id == $properties['plan']) {
					$props['price'] = $plan->price;
				}
			}
		}
		if (!empty($properties['coupon'])) {
			$props['discounts']['add']['inheritedFromId'] = $properties['coupon'];
		}
		
		if (!empty($properties['card'])) {
			$props['paymentMethodToken'] = $properties['card'];
		}
		
		Braintree_Subscription::update($this->id, $props);
		$this->braintree_subscription = null;
		
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
		Braintree_Subscription::cancel($this->id);
		$this->braintree_subscription = null;
		return $this;
	}
	
	/**
	 * Gets the native subscription response.
	 *
	 * @return Braintree_Customer
	 */
	public function getNativeResponse()
	{
		$this->info();
		return $this->braintree_subscription;
	}
}
