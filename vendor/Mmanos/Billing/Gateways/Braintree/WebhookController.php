<?php namespace Mmanos\Billing\Gateways\Braintree;

use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;
use Mmanos\Billing\Facades\Billing;
use Braintree_WebhookNotification;

class WebhookController extends \Mmanos\Billing\Gateways\WebhookController
{
	/**
	 * Handle a Braintree webhook call.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function handleWebhook()
	{
		// Initialize Braintree gateway (config vars).
		Billing::customer();
		
		$payload = Braintree_WebhookNotification::parse(
			Input::get('bt_signature'), Input::get('bt_payload')
		);
		
		$method = 'handle'.studly_case(str_replace('.', '_', $payload->kind));
		
		if (method_exists($this, $method)) {
			return $this->{$method}($payload);
		}
		else {
			return $this->missingMethod();
		}
	}
	
	/**
	 * Handle a canceled subscription from Braintree.
	 *
	 * @param Braintree_WebhookNotification $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleSubscriptionCanceled(Braintree_WebhookNotification $payload)
	{
		if ($payload->subscription->id) {
			if ($subscription = $this->getSubscription($payload->subscription->id)) {
				$subscription->subscription()->refresh();
			}
		}
		
		return new Response('Webhook Handled', 200);
	}
	
	/**
	 * Handle a subscription who's trial just ended from Braintree.
	 *
	 * @param Braintree_WebhookNotification $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleSubscriptionTrialEnded(Braintree_WebhookNotification $payload)
	{
		if ($payload->subscription->id) {
			if ($subscription = $this->getSubscription($payload->subscription->id)) {
				$subscription->fireSubscriptionEvent('trialWillEnd', array(
					'days' => 0,
				));
			}
		}
		
		return new Response('Webhook Handled', 200);
	}
	
	/**
	 * Handle a successful subscription (invoice) charge from Braintree.
	 *
	 * @param Braintree_WebhookNotification $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleSubscriptionChargedSuccessfully(Braintree_WebhookNotification $payload)
	{
		if ($payload->subscription->id && !empty($payload->subscription->transactions)) {
			if ($subscription = $this->getSubscription($payload->subscription->id)) {
				if ($customer = $subscription->customer()) {
					if ($invoice = $customer->invoices()->find($payload->subscription->transactions[0]->id)) {
						$customer->fireCustomerEvent('invoicePaymentSucceeded', $invoice);
					}
				}
			}
		}
		
		return new Response('Webhook Handled', 200);
	}
	
	/**
	 * Handle a failed subscription (invoice) charge from Braintree.
	 *
	 * @param Braintree_WebhookNotification $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleSubscriptionChargedUnsuccessfully(Braintree_WebhookNotification $payload)
	{
		if ($payload->subscription->id && !empty($payload->subscription->transactions)) {
			if ($subscription = $this->getSubscription($payload->subscription->id)) {
				if ($customer = $subscription->customer()) {
					if ($invoice = $customer->invoices()->find($payload->subscription->transactions[0]->id)) {
						$customer->fireCustomerEvent('invoicePaymentFailed', $invoice, array());
					}
				}
			}
		}
		
		return new Response('Webhook Handled', 200);
	}
}
