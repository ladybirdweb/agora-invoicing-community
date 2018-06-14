<?php

namespace Tests\Unit\Client\Account;

use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Tests\DBTestCase;

class OrderTest extends DBTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    /** @group getOrder */
    public function test_getOrder_viewingAllTheOrders()
    {

    	$this->withoutMiddleware();
    	$product = factory(Product::class)->create();
     	$this->getLoggedInUser();
    	$user = $this->user;
    	$user_id = $user->id;
    	$plan = factory(Plan::class)->create(['product'=>$product->id,'name'=>$product->name]);
    	$invoice = factory(Invoice::class)->create(['user_id'=> $user_id]);
    	$invoiceItem = InvoiceItem::create ([
            'invoice_id'       => $invoice->id,
            'product_name'     => 'Helpdesk Advance',
            'regular_price'    => 10000,
            'quantity'         => 1,
            'tax_name'         => 'CGST+SGST',
            'tax_percentage'   => 18,
            'subtotal'         => 11800,
            'domain'           => 'faveo.com',
             ]);
    	$order = factory(Order::class)->create(['invoice_id'=> $invoice->id,
        'invoice_item_id' => $invoiceItem->id,
        'client'=> $user_id,
        'product'=> $product->id]);
    	$subscription = Subscription::create([
    		'user_id'  => $user_id,
    		'plan_id'  => $plan->id,
    		'order_id' => $order->id,
    		'quantity' => 1,
    		'ends_at'  => '2019-06-13',
    		'version'  => 'v1.9.32',
    		'product_id' => $product->id,
    	]);
    	$response = $this->call('GET', 'my-order/'.$order->id,[
         'invoice' => $invoice,
         'items'   => $invoiceItem,
         'user'    => $user,
         'order'   => $order,
         'plan'    => $plan,
         'product' => $product,
		 'subscription' => $subscription,
        ]);
        $this->assertStringContainsSubstring($response->content(), 'Whoops');
    }
}
