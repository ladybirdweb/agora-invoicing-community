<?php

namespace Tests\Unit\Client\Checkout;

use App\Model\Order\Invoice;
use App\Model\Payment\Tax;
use App\Model\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class CheckoutTest extends DBTestCase
{
    use DatabaseTransactions;

    /** @group checkout */
    public function test_checkoutForm_movingToCheckoutPageWhenUserIsLoggedIn()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $product = factory(Product::class)->create();
        $invoice = factory(Invoice::class)->create(['user_id'=>$user->id]);
        \Cart::add([
        'id'         => $product->id,
        'name'       => $product->name,
        'price'      => $invoice->grand_total,
        'quantity'   => 1,
        'attributes' => [
            'tax'=> [
                0=> [
                'name'        => 'CGST+SGST',
                'c_gst'       => '9',
                's_gst'       => '9',
                'i_gst'       => '19',
                'ut_gst'      => null,
                'state'       => 'IN-KA',
                'origin_state'=> 'IN-KA',
                'tax_enable'  => '1',
                'rate'        => '18%',
                'status'      => 1,
            ], ],
            'currency'=> [
                0=> [
                    'id'    => $product->id,
                    'code'  => 'INR',
                    'symbol'=> '₹',
                    'name'  => 'Indian Rupee',
                ], ], ],
        ]);

        $response = $this->call('GET', 'checkout', [
         'domain' => 'faveo.com',
        ]);
        $response->assertStatus(302);
    }
}
