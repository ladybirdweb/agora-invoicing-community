<?php namespace Mmanos\Billing\CustomerBillableTrait;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;

class Invoice
{
	/**
	 * Customer model.
	 *
	 * @var \Illuminate\Database\Eloquent\Model
	 */
	protected $model;
	
	/**
	 * Invoice gateway instance.
	 *
	 * @var \Mmanos\Billing\Gateways\InvoiceInterface
	 */
	protected $invoice;
	
	/**
	 * Invoice info array.
	 *
	 * @var array
	 */
	protected $info;
	
	/**
	 * Create a new CustomerBillableTrait Invoice instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model       $model
	 * @param \Mmanos\Billing\Gateways\InvoiceInterface $invoice
	 * 
	 * @return void
	 */
	public function __construct(\Illuminate\Database\Eloquent\Model $model, \Mmanos\Billing\Gateways\InvoiceInterface $invoice)
	{
		$this->model = $model;
		$this->invoice = $invoice;
		$this->info = $invoice->info();
	}
	
	/**
	 * Return an array of line items for this invoice.
	 *
	 * @return array
	 */
	public function items()
	{
		$items = array();
		
		foreach ($this->items as $item) {
			$items[] = new InvoiceItem($this->model, $this->invoice, $item);
		}
		
		return $items;
	}
	
	/**
	 * Get the invoice view.
	 *
	 * @param array $data
	 * 
	 * @return View
	 */
	public function view(array $data = array())
	{
		return View::make(
			'laravel-billing::invoice',
			array_merge($data, array('invoice' => $this))
		);
	}
	
	/**
	 * Get the rendered HTML content of the invoice view.
	 *
	 * @param array $data
	 * 
	 * @return string
	 */
	public function render(array $data = array())
	{
		return $this->view($data)->render();
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
	 * Dynamically check a values existence from the invoice.
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
	 * Dynamically get values from the invoice.
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
