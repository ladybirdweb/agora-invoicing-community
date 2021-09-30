<?php

namespace Razorpay\Tests;

use Razorpay\Api\Api;
use Razorpay\Api\Request;

class TestCase extends \PHPUnit_Framework_TestCase
{
    
    public function setUp()
    {
        $apiKey = 'rzp_test_1DP5mmOlF5G5ag';
        $apiSecret = 'thisissupersecret';
        
        $this->api = new Api( $apiKey, $apiSecret);
    }
}