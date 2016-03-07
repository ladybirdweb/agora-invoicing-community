<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;

class Creditcard
{
	/**
	 * Customer model.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	
	/**
	 * Credit card.
	 *
	 * @var \Mmanos\Billing\Gateways\CardInterface
	 */
	protected $card;
	
	/**
	 * Card info array.
	 *
	 * @var array
	 */
	protected $info;
	
	/**
	 * Create a new CustomerBillableTrait Creditcard instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model    $model
	 * @param \Mmanos\Billing\Gateways\CardInterface $card
	 * @param array                                  $info
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model, \Mmanos\Billing\Gateways\CardInterface $card, array $info = null)
	{
		$this->model = $model;
		$this->card = $card;
		
		if (empty($info)) {
			$info = $card->info();
		}
		
		$this->info = $info;
	}
	
	/**
	 * Update this customer credit card in the billing gateway.
	 *
	 * @param array $properties
	 * 
	 * @return Creditcard
	 */
	public function update(array $properties)
	{
		if (!$this->model->readyForBilling()) {
			return $this;
		}
		
		$this->card->update($properties);
		$this->info = $this->card->info();
		
		$cards = array();
		foreach ($this->model->billing_cards as $c) {
			$cards[] = (Arr::get($c, 'id') == $this->id) ? $this->info : $c;
		}
		$this->model->billing_cards = $cards;
		$this->model->save();
		
		return $this;
	}
	
	/**
	 * Delete this customer credit card in the billing gateway.
	 *
	 * @return Creditcard
	 */
	public function delete()
	{
		if (!$this->model->readyForBilling()) {
			return $this;
		}
		
		$this->card->delete();
		
		$cards = array();
		foreach ($this->model->billing_cards as $c) {
			if (Arr::get($c, 'id') != $this->id) {
				$cards[] = $c;
			}
		}
		$this->model->billing_cards = $cards;
		$this->model->save();
		
		// Refresh all subscription records that referenced this card.
		if ($subscriptions = $this->model->subscriptionModelsArray()) {
			foreach ($subscriptions as $subscription) {
				if ($subscription->billingIsActive() && $subscription->billing_card == $this->id) {
					$subscription->subscription()->refresh();
				}
			}
		}
		
		$this->info = array('id' => $this->id);
		
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
	 * Dynamically check a values existence from the creditcard.
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
	 * Dynamically get values from the creditcard.
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
