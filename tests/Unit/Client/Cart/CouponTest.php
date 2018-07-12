<?php

namespace Tests\Unit\Client\Cart;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Model\Product\Product;
use Tests\DBTestCase;

class CouponTest extends DBTestCase
{
	use DatabaseTransactions;
     

      /** @group coupon */
    public function test_addCouponUpdate_whenValidCouponProvided()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $product = factory(Product::class)->create();
        $response = $this->call('POST','pricing/update',[
        'coupon' => 'FAVEOCOUPON',
        ]);

    }
}
