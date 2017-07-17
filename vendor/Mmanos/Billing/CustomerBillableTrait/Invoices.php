<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;

class Invoices
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
	 * Create a new CustomerBillableTrait Invoices instance.
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
	 * Fetch the credit invoice.
	 *
	 * @return array
	 */
	public function get()
	{
		$invoices = array();
		
		foreach ($this->model->gatewayCustomer()->invoices() as $invoice) {
			$invoices[] = new Invoice($this->model, $invoice);
		}
		
		return $invoices;
	}
	
	/**
	 * Find and return the first credit card.
	 *
	 * @return Invoice
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
	 * @return Invoice
	 */
	public function find($id)
	{
		try {
			return new Invoice($this->model, $this->model->gatewayCustomer()->invoice($id));
		} catch (\Exception $e) {}
		
		return null;
	}
}
