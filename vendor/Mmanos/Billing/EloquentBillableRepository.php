<?php namespace Mmanos\Billing;

use Illuminate\Support\Facades\Config;

class EloquentBillableRepository
{
	/**
	 * Find a billing customer implementation by billing ID.
	 *
	 * @param mixed $billing_id
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public static function findCustomer($billing_id)
	{
		if (!$billing_id) {
			return null;
		}
		
		foreach (Config::get('laravel-billing::customer_models') as $model) {
			$query = new $model;
			
			if ($customer = $query->where('billing_id', $billing_id)->first()) {
				return $customer;
			}
		}
		
		return null;
	}
	
	/**
	 * Find a billing subscription implementation by subscription ID.
	 *
	 * @param mixed $subscription_id
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public static function findSubscription($subscription_id)
	{
		if (!$subscription_id) {
			return null;
		}
		
		foreach (Config::get('laravel-billing::subscription_models') as $model) {
			$query = new $model;
			
			if ($subscription = $query->where('billing_subscription', $subscription_id)->first()) {
				return $subscription;
			}
		}
		
		return null;
	}
}
