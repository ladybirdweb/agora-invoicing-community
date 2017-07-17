<?php namespace Mmanos\Billing\Gateways\Stripe;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class WebhookController extends \Mmanos\Billing\Gateways\WebhookController
{
	/**
	 * Handle a Stripe webhook call.
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function handleWebhook()
	{
		$payload = (array) json_decode(Request::getContent(), true);
		
		$method = 'handle'.studly_case(str_replace('.', '_', $payload['type']));
		
		if (method_exists($this, $method)) {
			return $this->{$method}($payload);
		}
		else {
			return $this->missingMethod();
		}
	}
	
	/**
	 * Handle a newly created Stripe invoice.
	 *
	 * @param array $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleInvoiceCreated(array $payload)
	{
		if ($customer = $this->getCustomer($payload['data']['object']['customer'])) {
			$customer->fireCustomerEvent(
				'invoiceCreated',
				$customer->invoices()->find($payload['data']['object']['id'])
			);
		}
		
		return new Response('Webhook Handled', 200);
	}
	
	/**
	 * Handle a failed payment from a Stripe invoice.
	 *
	 * @param array $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleInvoicePaymentFailed(array $payload)
	{
		if ($customer = $this->getCustomer($payload['data']['object']['customer'])) {
			$next_attempt = Arr::get($payload, 'data.object.next_payment_attempt');
			
			$data = array(
				'attempt_count' => Arr::get($payload, 'data.object.attempt_count'),
				'next_attempt' => Carbon::createFromTimeStamp($next_attempt),
			);
			
			$customer->fireCustomerEvent(
				'invoicePaymentFailed',
				$customer->invoices()->find($payload['data']['object']['id']),
				$data
			);
		}
		
		return new Response('Webhook Handled', 200);
	}
	
	/**
	 * Handle a successful payment from a Stripe invoice.
	 *
	 * @param array $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleInvoicePaymentSucceeded(array $payload)
	{
		if ($customer = $this->getCustomer($payload['data']['object']['customer'])) {
			$customer->fireCustomerEvent(
				'invoicePaymentSucceeded',
				$customer->invoices()->find($payload['data']['object']['id'])
			);
		}
		
		return new Response('Webhook Handled', 200);
	}
	
	/**
	 * Handle a Stripe subscription that has been canceled/deleted.
	 *
	 * @param array $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleCustomerSubscriptionDeleted(array $payload)
	{
		if ($subscription = $this->getSubscription($payload['data']['object']['id'])) {
			if ($subscription->billingIsActive()) {
				$subscription->subscription()->refresh();
			}
		}
		
		return new Response('Webhook Handled', 200);
	}
	
	/**
	 * Handle a Stripe subscription who's trial is going to end in three days.
	 *
	 * @param array $payload
	 * 
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	protected function handleCustomerSubscriptionTrialWillEnd(array $payload)
	{
		if ($subscription = $this->getSubscription($payload['data']['object']['id'])) {
			$subscription->fireSubscriptionEvent('trialWillEnd', array(
				'days' => 3,
			));
		}
		
		return new Response('Webhook Handled', 200);
	}
}
