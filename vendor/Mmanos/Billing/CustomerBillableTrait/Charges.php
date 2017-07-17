<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;

class Charges
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
	 * The credit card token to assign to the charge.
	 *
	 * @var string
	 */
	protected $card_token;
	
	/**
	 * The credit card id to assign to the charge.
	 *
	 * @var string
	 */
	protected $card;
	
	/**
	 * Create a new CustomerBillableTrait Charges instance.
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
	 * Fetch the credit charge.
	 *
	 * @return array
	 */
	public function get()
	{
		$charges = array();
		
		foreach ($this->model->gatewayCustomer()->charges() as $charge) {
			$charges[] = new Charge($this->model, $charge);
		}
		
		return $charges;
	}
	
	/**
	 * Find and return the first credit card.
	 *
	 * @return Charge
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
	 * @return Charge
	 */
	public function find($id)
	{
		try {
			return new Charge($this->model, $this->model->gatewayCustomer()->charge($id));
		} catch (\Exception $e) {}
		
		return null;
	}
	
	/**
	 * Create this charge in the billing gateway.
	 *
	 * @param int   $amount
	 * @param array $properties
	 * 
	 * @return Charge
	 */
	public function create($amount, array $properties = array())
	{
		if (!$this->model->readyForBilling()) {
			if ($this->card_token) {
				$this->model->billing()->withCardToken($this->card_token)->create($properties);
				if (!empty($this->model->billing_cards)) {
					$this->card_token = null;
				}
			}
			else {
				$this->model->billing()->create($properties);
			}
		}
		
		if ($this->card_token) {
			$this->card = $this->model->creditcards()->create($this->card_token)->id;
			$this->card_token = null;
		}
		
		$gateway_charge = \Mmanos\Billing\Facades\Billing::charge(null, $this->model->gatewayCustomer())->create($amount, array_merge($properties, array(
			'card_token' => $this->card_token,
			'card'       => $this->card,
		)));
		
		return new Charge($this->model, $gateway_charge);
	}
	
	/**
	 * The credit card token to assign to a new charge.
	 *
	 * @param string $card_token
	 * 
	 * @return Charges
	 */
	public function withCardToken($card_token)
	{
		$this->card_token = $card_token;
		return $this;
	}
	
	/**
	 * The credit card id or array to assign to a new charge.
	 *
	 * @param string|array $card
	 * 
	 * @return Charges
	 */
	public function withCard($card)
	{
		$this->card = is_array($card) ? Arr::get($card, 'id') : $card;
		return $this;
	}
}
