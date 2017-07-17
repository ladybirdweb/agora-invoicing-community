<?php namespace Mmanos\Billing\Gateways;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
	/**
	 * Get the billable customer entity instance by Stripe ID.
	 *
	 * @param string $stripe_id
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	protected function getCustomer($stripe_id)
	{
		return \Mmanos\Billing\EloquentBillableRepository::findCustomer($stripe_id);
	}
	
	/**
	 * Get the billable subscription entity instance by Stripe ID.
	 *
	 * @param string $stripe_id
	 * 
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	protected function getSubscription($stripe_id)
	{
		return \Mmanos\Billing\EloquentBillableRepository::findSubscription($stripe_id);
	}
	
	/**
	 * Handle calls to missing methods on the controller.
	 *
	 * @param array $parameters
	 * 
	 * @return mixed
	 */
	public function missingMethod($parameters = array())
	{
		return new Response;
	}
}
