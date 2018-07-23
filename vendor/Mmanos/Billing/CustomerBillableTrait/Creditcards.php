<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;

class Creditcards
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
	 * Create a new CustomerBillableTrait Creditcards instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model)
	{
		$this->model = $model;
	}
	
	/**
	 * Fetch the credit cards.
	 *
	 * @return array
	 */
	public function get()
	{
		$cards = array();
		
		foreach ($this->model->billing_cards as $info) {
			$cards[] = new Creditcard(
				$this->model,
				$this->model->gatewayCustomer()->card(Arr::get($info, 'id')),
				$info
			);
		}
		
		return $cards;
	}
	
	/**
	 * Find and return the first credit card.
	 *
	 * @return Creditcard
	 */
	public function first()
	{
		if (empty($this->model->billing_cards)) {
			return null;
		}
		
		$info = $this->model->billing_cards[0];
		
		return new Creditcard(
			$this->model,
			$this->model->gatewayCustomer()->card(Arr::get($info, 'id')),
			$info
		);
	}
	
	/**
	 * Find and return a credit card.
	 *
	 * @param mixed $id
	 * 
	 * @return Creditcard
	 */
	public function find($id)
	{
		foreach ($this->model->billing_cards as $info) {
			if (Arr::get($info, 'id') == $id) {
				return new Creditcard(
					$this->model,
					$this->model->gatewayCustomer()->card(Arr::get($info, 'id')),
					$info
				);
			}
		}
		
		return null;
	}
	
	/**
	 * Add a new credit card to this customer in the billing gateway.
	 *
	 * @param string $card_token
	 * 
	 * @return Creditcard
	 */
	public function create($card_token)
	{
		if (!$this->model->readyForBilling()) {
			return;
		}
		
		$card = new Creditcard(
			$this->model,
			$this->model->gatewayCustomer()->card()->create($card_token)
		);
		
		$this->model->billing_cards = array_merge($this->model->billing_cards, array($card->toArray()));
		$this->model->save();
		
		return $card;
	}
}
