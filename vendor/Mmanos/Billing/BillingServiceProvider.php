<?php namespace Mmanos\Billing;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class BillingServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('mmanos/laravel-billing');
	}
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('billing.gateway', function ($app) {
			switch (Config::get('laravel-billing::default')) {
				case 'stripe':
					return new \Mmanos\Billing\Gateways\Stripe\Gateway;
				case 'braintree':
					return new \Mmanos\Billing\Gateways\Braintree\Gateway;
				case 'local':
					return new \Mmanos\Billing\Gateways\Local\Gateway;
				default:
					return null;
			}
		});
		
		$this->app->bindShared('command.laravel-billing.customer-table', function ($app) {
			return new CustomerTableCommand;
		});
		$this->commands('command.laravel-billing.customer-table');
		
		$this->app->bindShared('command.laravel-billing.subscription-table', function ($app) {
			return new SubscriptionTableCommand;
		});
		$this->commands('command.laravel-billing.subscription-table');
		
		$this->app->bindShared('command.laravel-billing.local-create-plan', function ($app) {
			return new LocalCreatePlanCommand;
		});
		$this->commands('command.laravel-billing.local-create-plan');
		
		$this->app->bindShared('command.laravel-billing.local-create-coupon', function ($app) {
			return new LocalCreateCouponCommand;
		});
		$this->commands('command.laravel-billing.local-create-coupon');
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}
}
