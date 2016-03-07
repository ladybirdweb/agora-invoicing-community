<?php namespace Mmanos\Billing\Facades;

class Billing extends \Illuminate\Support\Facades\Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'billing.gateway';
	}
}
