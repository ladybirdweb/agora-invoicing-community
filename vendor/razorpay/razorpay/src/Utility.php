<?php

namespace Razorpay\Api;

class Utility
{
    const SHA256 = 'sha256';

    public function verifyPaymentSignature($attributes)
    {
        $expectedSignature = $attributes['razorpay_signature'];
        $orderId = $attributes['razorpay_order_id'];
        $paymentId = $attributes['razorpay_payment_id'];

        $payload = $orderId . '|' . $paymentId;

        return self::verifySignature($payload, $expectedSignature);
    }

    public function verifyWebhookSignature($payload, $actualSignature, $secret)
    {
        return self::verifySignature($payload, $expectedSignature);
    }

    public function verifySignature($payload, $actualSignature, $secret)
    {
        $expectedSignature = hash_hmac(self::SHA256, $payload, $secret);

        // Use lang's built-in hash_equals if exists to mitigate timing attacks
        if (function_exists('hash_equals'))
        {
            $verified = hash_equals($expectedSignature, $actualSignature);
        }
        else
        {
            $verified = $this->hashEquals($expectedSignature, $actualSignature);
        }

        if ($verified === false)
        {
            throw new Errors\SignatureVerificationError(
                'Invalid signature passed');
        }
    }

    private function hashEquals($expectedSignature, $actualSignature)
    {
        if (strlen($expectedSignature) === strlen($actualSignature))
        {
            $res = $expectedSignature ^ $actualSignature;
            $return = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $return |= ord($res[$i]);
            }

            return ($return === 0);
        }

        return false;
    }
}
