<?php

namespace Razorpay\Tests;

use Razorpay\Api\Request;

class TokenTest extends TestCase
{
    /**
     * Specify unique payment id, token id & customer id
     * for example pay_IEczPDny6uzSnx, cust_IEcn7UdBOFmaNi &
     * token_IEcux6sQtS8eLx
     */

    private $paymentId = "pay_IEczPDny6uzSnx";

    private $customerId = "cust_IEcn7UdBOFmaNi";

    private $tokenId = "token_IEcux6sQtS8eLx";

    public function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * Create registration link
     */
    public function testCreateRegistrationLink()
    {
        $data = $this->api->subscription->createSubscriptionRegistration(array('customer' => array('name' => 'Gaurav Kumar','email' => 'gaurav.kumar@example.com','contact' => '9123456780'),'amount' => 0,'currency' => 'INR','type' => 'link','description' => '12 p.m. Meals','subscription_registration' => array('method' => 'nach','auth_type' => 'physical','bank_account' => array('beneficiary_name' => 'Gaurav Kumar','account_number' => '11214311215411','account_type' => 'savings','ifsc_code' => 'HDFC0001233'),'nach' => array('form_reference1' => 'Recurring Payment for Gaurav Kumar','form_reference2' => 'Method Paper NACH'),'expire_at' => strtotime('+1 day'),'max_amount' => 50000),'receipt' => 'Receipt No. '.time(),'sms_notify' => 1,'email_notify' => 1,'expire_by' => strtotime('+1 day'),'notes' => array('note_key 1' => 'Beam me up Scotty','note_key 2' => 'Tea. Earl Gray. Hot.')));

        $this->assertTrue(is_array($data->toArray()));

        $this->assertTrue(in_array('id',$data->toArray()));
    }

    /**
     * Fetch token by payment id
     */
    public function testFetchTokenByPaymentId()
    {
        $data = $this->api->payment->fetch($this->paymentId);

        $this->assertTrue(is_array($data->toArray()));
        
        $this->assertTrue(in_array('payment',$data->toArray()));
    }

    /**
     * Fetch particular token
     */
    public function testFetchTokenByCustomerId()
    {
        $data = $this->api->customer->fetch($this->customerId)->tokens()->fetch($this->tokenId);

        $this->assertTrue(is_array($data->toArray()));
        
        $this->assertTrue(in_array('payment',$data->toArray()));
    }

}