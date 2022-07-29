<?php

namespace Razorpay\Tests;

use Razorpay\Api\Request;

class SignatureVerificationTest extends TestCase
{
    private static $subscriptionId;

    public function setUp(): void
    {
        parent::setUp();
    }
    
    /**
     * Verify Payment verification
     */
    public function testPaymentVerification()
    {
        $orderId = 'order_IEIaMR65cu6nz3';
        $paymentId = 'pay_IH4NVgf4Dreq1l';
        $signature = '0d4e745a1838664ad6c9c9902212a32d627d68e917290b0ad5f08ff4561bc50f';

        $this->assertTrue(true,$this->api->utility->verifyPaymentSignature(array(
          'razorpay_order_id' => $orderId,
          'razorpay_payment_id' => $paymentId,
          'razorpay_signature' => $signature
        )));
    }
    
    /**
     * Verify PaymentLink verification
     */
    public function testPaymentLinkVerification()
    {
        $paymentLinkId = 'plink_IH3cNucfVEgV68';
        $paymentId = 'pay_IH3d0ara9bSsjQ';
        $paymentLinkReferenceId = 'TSsd1989';
        $paymentLinkStatus = 'paid';
        $signature = '07ae18789e35093e51d0a491eb9922646f3f82773547e5b0f67ee3f2d3bf7d5b';

        $this->assertTrue(true,$this->api->utility->verifyPaymentSignature(array(
          'razorpay_payment_link_id' => $paymentLinkId,
          'razorpay_payment_link_reference_id' => $paymentLinkReferenceId,
          'razorpay_payment_link_status' => $paymentLinkStatus,
          'razorpay_payment_id' => $paymentId,
          'razorpay_signature' => $signature
        )));
    }

    /**
     * Verify Subscription verification
     */
    public function testSubscriptionVerification()
    {
        $subscriptionId = 'sub_ID6MOhgkcoHj9I';
        $paymentId = 'pay_IDZNwZZFtnjyym';
        $signature = '601f383334975c714c91a7d97dd723eb56520318355863dcf3821c0d07a17693';

        $this->assertTrue(true,$this->api->utility->verifyPaymentSignature(array(
          'razorpay_subscription_id' => $subscriptionId,
          'razorpay_payment_id' => $paymentId,
          'razorpay_signature' => $signature
        )));
    }
}