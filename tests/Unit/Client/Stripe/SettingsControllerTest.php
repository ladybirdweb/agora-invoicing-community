<?php

namespace Tests\Unit\Client\Stripe;
use Mockery;
use App\Plugins\Stripe\Controllers\SettingsController;
use Tests\DBTestCase;
use Validator;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use App\ApiKey;
use Tests\MockStripeClient;
use Illuminate\Support\Testing\Fakes\PendingFake;
class SettingsControllerTest extends DBTestCase
{

    // Helper method to set up the mock for the Stripe client
    protected function setupStripeClientMock($expectedArguments, $status)
    {
        $stripeClientConstructorMock = Mockery::mock('\Stripe\StripeClient');
        $stripeClientConstructorMock->shouldReceive('paymentIntents->confirm')
            ->with('payment_intent_id', $expectedArguments)
            ->andReturn(['status' => $status]);
        return $stripeClientConstructorMock;
    }


    // Helper method to set up the mock for the request
    protected function setupRequestMock($requestData)
    {
        $requestMock = Mockery::mock(Request::class);
        $requestMock->shouldReceive('all')->andReturn($requestData);
        $requestMock->shouldReceive('isPrecognitive')->andReturn(null);
        foreach ($requestData as $key => $value) {
            $requestMock->shouldReceive('get')->with($key)->andReturn($value);
        }
        \DB::table('api_keys')->where('id',1)->update(['stripe_secret' => 'sk_test_FIPEe0BihQ4Rn2exN1BhOotg']);
         return $requestMock;
    }
    
    // Helper method to set up the Auth user
    protected function SetAuthUser()
    {
        $user = \Auth::shouldReceive('user')->andReturn((object) [
            'first_name' => 'sowmi',
            'last_name' => 's',
            'email' => 'sowmi@gmail.com',
        ]);
        return $user;

    }


     // Test case for handling 3DS authentication
    public function test_handlePayment_3DS_authentication()
    {
        $requestData = ['card_no' => '4000003560000008', 'exp_month' => '12', 'exp_year' => '25', 'cvv' => '123'];
        $expectedArguments = ['payment_method' => 'pm_card_visa', 'return_url' => 'https://example.com/return-url'];
        $status = 'require_action';
        $stripeClientConstructorMock = $this->setupStripeClientMock($expectedArguments, $status);
        $requestMock = $this->setupRequestMock($requestData);
        $this->SetAuthUser();
        $controller = new SettingsController($stripeClientConstructorMock);
        $response = $controller->handlePayment($requestMock, 50, 'INR', 'https://example.com/return-url', null);
        $this->assertEquals('requires_action', $response['status']);
        $this->assertEquals('https://example.com/return-url', $response->next_action->redirect_to_url->return_url);
    }

     // Test case for handling incorrect card values
    public function test_handlePayment_return_exception_incorrect_values()
    {
        $requestData = ['card_no' => '12345678953', 'exp_month' => '12', 'exp_year' => '25', 'cvv' => '123'];
        $expectedArguments = ['payment_method' => 'pm_card_visa', 'return_url' => 'https://example.com/return-url'];
        $status = 'require_action';
        $stripeClientConstructorMock = $this->setupStripeClientMock($expectedArguments, $status);
        $requestMock = $this->setupRequestMock($requestData);
        $this->SetAuthUser();
        $controller = new SettingsController($stripeClientConstructorMock);
        $response = $controller->handlePayment($requestMock, 50, 'INR', 'https://example.com/return-url', null);
        $this->assertEquals('Your card number is incorrect.', $response->getSession()->get('fails'));
    }


}
