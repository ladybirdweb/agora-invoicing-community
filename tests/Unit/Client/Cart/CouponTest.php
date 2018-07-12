<?php

namespace Tests\Unit\Client\Cart;

use App\Model\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
        $response = $this->call('POST', 'pricing/update', [
        'coupon' => 'FAVEOCOUPON',
        ]);
    }
}
