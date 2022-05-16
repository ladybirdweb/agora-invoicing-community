<?php

namespace Razorpay\Tests;

use Razorpay\Api\Api;
use Razorpay\Api\Request;
use PHPUnit\Framework\TestCase as PhpUnitTest;

class TestCase extends PhpUnitTest
{
    
    public function setUp(): void
    {
        $apiKey = "";
        $apiSecret = "";
        
        $this->api = new Api( $apiKey, $apiSecret);
    }
}