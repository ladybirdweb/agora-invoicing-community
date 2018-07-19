<?php

namespace Tests\Unit\Client\Cart;

use App\Model\Order\Invoice;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class ClearCartTest extends DBTestCase
{
    use DatabaseTransactions;

    public function test_cart_clear_whenCartIsCleared()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $invoice = factory(Invoice::class)->create(['user_id'=>$user->id]);
        (\Session::put('domain', '=', 'ashu.com'));
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => $invoice->grand_total,
            'quantity'   => 1,
            'attributes' => [],
        ]);
        $response = $this->call('GET', 'cart/clear');
        $response->assertStatus(302);
    }
}
