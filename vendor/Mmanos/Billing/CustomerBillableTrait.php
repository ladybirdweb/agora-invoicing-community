<?php namespace Mmanos\Billing;

use Mmanos\Billing\Facades\Billing;
use LogicException;

trait CustomerBillableTrait
{
	/**
	 * Return the gateway customer object for this user.
	 *
	 * @return Mmanos\Billing\Gateways\CustomerInterface
	 */
	public function gatewayCustomer()
	{
		if (!$this->readyForBilling()) {
			return null;
		}
		
		return Billing::customer($this->billing_id);
	}
	
	/**
	 * Return a customer billing helper object.
	 *
	 * @return CustomerBillableTrait\Billing
	 */
	public function billing()
	{
		return new CustomerBillableTrait\Billing($this);
	}
	
	/**
	 * Return a customer creditcards helper object.
	 *
	 * @return CustomerBillableTrait\Creditcards
	 */
	public function creditcards()
	{
		return new CustomerBillableTrait\Creditcards($this);
	}
	
	/**
	 * Return a customer invoices helper object.
	 *
	 * @return CustomerBillableTrait\Invoices
	 */
	public function invoices()
	{
		return new CustomerBillableTrait\Invoices($this);
	}
	
	/**
	 * Return a customer charges helper object.
	 *
	 * @return CustomerBillableTrait\Charges
	 */
	public function charges()
	{
		return new CustomerBillableTrait\Charges($this);
	}
	
	/**
	 * Return a customer subscriptions helper object.
	 *
	 * @param mixed $plan
	 * 
	 * @return CustomerBillableTrait\Subscriptions
	 */
	public function subscriptions($plan = null)
	{
		return new CustomerBillableTrait\Subscriptions($this, $plan);
	}
	
	/**
	 * Determine if the entity is a Billing customer.
	 *
	 * @return bool
	 */
	public function readyForBilling()
	{
		return !empty($this->billing_id);
	}
	
	/**
	 * Return the Eloquent models acting as billing subscriptions for this customer model.
	 * The subscription models can be defined in one of the following ways:
	 * - A 'subscriptionmodels' relationship on this model.
	 * - A 'subscriptionmodels' method on this model that returns one of the following:
	 *   - A collection
	 *   - An array of collections
	 *   - An array of models
	 *
	 * @return array
	 */
	public function subscriptionModelsArray()
	{
		// Note: Laravel throws a LogicException if a subscriptions method exists but
		// doesn't return a valid relationship.
		try {
			if ($collection = $this->subscriptionmodels) {
				return $collection->all();
			}
		} catch (LogicException $e) {}
		
		if (method_exists($this, 'subscriptionmodels')) {
			$subscriptions = array();
			
			foreach ($this->subscriptionmodels() as $value) {
				if ($value instanceof \Illuminate\Support\Collection) {
					$subscriptions = array_merge($subscriptions, $value->all());
				}
				else if ($value instanceof \Illuminate\Database\Eloquent\Model) {
					$subscriptions[] = $value;
				}
			}
			
			return $subscriptions;
		}
		
		// Check for customer/subscription on the same model.
		if (method_exists($this, 'gatewaySubscription')) {
			return array($this);
		}
		
		return null;
	}
	
	/**
	 * Getter for billing_cards property.
	 *
	 * @param string $value
	 * 
	 * @return array
	 */
	public function getBillingCardsAttribute($value)
	{
		return $value ? json_decode($value, true) : array();
	}
	
	/**
	 * Setter for billing_cards property.
	 *
	 * @param array $value
	 * 
	 * @return void
	 */
	public function setBillingCardsAttribute($value)
	{
		$this->attributes['billing_cards'] = empty($value) ? null : json_encode($value);
	}
	
	/**
	 * Getter for billing_discounts property.
	 *
	 * @param string $value
	 * 
	 * @return array
	 */
	public function getBillingDiscountsAttribute($value)
	{
		return $value ? json_decode($value, true) : array();
	}
	
	/**
	 * Setter for billing_discounts property.
	 *
	 * @param array $value
	 * 
	 * @return void
	 */
	public function setBillingDiscountsAttribute($value)
	{
		$this->attributes['billing_discounts'] = empty($value) ? null : json_encode($value);
	}
	
	/**
	 * Register a customerCreated model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function customerCreated($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('customerCreated', $callback);
	}
	
	/**
	 * Register a customerDeleted model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function customerDeleted($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('customerDeleted', $callback);
	}
	
	/**
	 * Register a creditcardAdded model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function creditcardAdded($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('creditcardAdded', $callback);
	}
	
	/**
	 * Register a creditcardRemoved model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function creditcardRemoved($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('creditcardRemoved', $callback);
	}
	
	/**
	 * Register a creditcardUpdated model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function creditcardUpdated($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('creditcardUpdated', $callback);
	}
	
	/**
	 * Register a creditcardChanged model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function creditcardChanged($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('creditcardChanged', $callback);
	}
	
	/**
	 * Register a discountAdded model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function discountAdded($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('discountAdded', $callback);
	}
	
	/**
	 * Register a discountRemoved model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function discountRemoved($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('discountRemoved', $callback);
	}
	
	/**
	 * Register a discountUpdated model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function discountUpdated($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('discountUpdated', $callback);
	}
	
	/**
	 * Register a discountChanged model event with the dispatcher.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function discountChanged($callback)
	{
		static::listenForCustomerEvents();
		static::registerModelEvent('discountChanged', $callback);
	}
	
	/**
	 * Register a invoiceCreated model event with the dispatcher.
	 * Triggered via webhook.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function invoiceCreated($callback)
	{
		static::registerModelEvent('invoiceCreated', $callback);
	}
	
	/**
	 * Register a invoicePaymentSucceeded model event with the dispatcher.
	 * Triggered via webhook.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function invoicePaymentSucceeded($callback)
	{
		static::registerModelEvent('invoicePaymentSucceeded', $callback);
	}
	
	/**
	 * Register a invoicePaymentFailed model event with the dispatcher.
	 * Triggered via webhook.
	 *
	 * @param \Closure|string $callback
	 * 
	 * @return void
	 */
	public static function invoicePaymentFailed($callback)
	{
		static::registerModelEvent('invoicePaymentFailed', $callback);
	}
	
	/**
	 * Fire the given event for the model.
	 *
	 * @param string $event
	 * 
	 * @return mixed
	 */
	public function fireCustomerEvent($event)
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
	protected static function listenForCustomerEvents()
	{
		static $listening_for_customer_events;
		if ($listening_for_customer_events) {
			return;
		}
		
		static::saved(function ($model) {
			$original = $model->getOriginal();
			
			if ($model->isDirty('billing_id')) {
				if (empty($original['billing_id']) && !empty($model->billing_id)) {
					$model->fireCustomerEvent('customerCreated');
				}
				else if (empty($model->billing_id) && !empty($original['billing_id'])) {
					$model->fireCustomerEvent('customerDeleted');
				}
			}
			if ($model->isDirty('billing_cards')) {
				if (count($model->billing_cards) > count(json_decode($model->getOriginal('billing_cards'), true))) {
					$model->fireCustomerEvent('creditcardAdded');
				}
				else if (count($model->billing_cards) < count(json_decode($model->getOriginal('billing_cards'), true))) {
					$model->fireCustomerEvent('creditcardRemoved');
				}
				else if (!empty($model->billing_cards)) {
					$model->fireCustomerEvent('creditcardUpdated');
				}
				$model->fireCustomerEvent('creditcardChanged');
			}
			if ($model->isDirty('billing_discounts')) {
				if (count($model->billing_discounts) > count(json_decode($model->getOriginal('billing_discounts'), true))) {
					$model->fireCustomerEvent('discountAdded');
				}
				else if (count($model->billing_discounts) < count(json_decode($model->getOriginal('billing_discounts'), true))) {
					$model->fireCustomerEvent('discountRemoved');
				}
				else if (!empty($model->billing_discounts)) {
					$model->fireCustomerEvent('discountUpdated');
				}
				$model->fireCustomerEvent('discountChanged');
			}
		});
		
		$listening_for_customer_events = true;
	}
}
