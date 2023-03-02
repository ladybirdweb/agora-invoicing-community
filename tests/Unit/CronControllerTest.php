<?php

namespace Tests\Unit;

use App\ApiKey;
use App\Http\Controllers\Common\CronController;
use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Tests\DBTestCase;

class CronControllerTest extends DBTestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_autoRenewal_return_null_when_zero_expired_subscription()
    {
        $stripeSecretKey = ApiKey::create(['stripe_secret' => 'sk_test_FIPEe0BihQ4Rn2exN1BhOotg', 'rzp_key' => 'rzp_test_fNDuvutBRXJLkQ', 'rzp_secret' => 'ObVJAj8L2e7V9RLOQkcdLtSw']);
        $date = '2022-03-02 18:15:02';
        $product = Product::create(['name' => 'Helpdesk Advance']);
        $user = User::factory()->create(['id' => mt_rand(1, 999), 'role' => 'user', 'country' => 'IN']);

        $invoice = Invoice::factory()->create(['user_id' => $user->id]);
        $invoiceItem = InvoiceItem::create(['invoice_id' => $invoice->id, 'product_name' => $product->name]);
        $order = Order::create(['client' => $user->id, 'order_status' => 'executed',
            'product' => 'Helpdesk Advance', 'number' => mt_rand(100000, 999999), 'invoice_id' => $invoice->id, ]);
        $permissions = 'No Permissions';
        $plan = Plan::create(['id' => 'mt_rand(1,99)', 'name' => 'Hepldesk 1 year', 'product' => $product->id, 'days' => 365]);
        $subscription = Subscription::create(['plan_id' => $plan->id, 'order_id' => $order->id, 'product_id' => $product->id, 'version' => 'v6.0.0', 'update_ends_at' => $date]);
        $response = (new CronController())->autoRenewal();
        $this->assertEquals(null, $response);
    }

    public function test_autoRenewal_return_onday_expired_subscription()
    {
        $stripeSecretKey = ApiKey::create(['stripe_secret' => 'sk_test_FIPEe0BihQ4Rn2exN1BhOotg', 'rzp_key' => 'rzp_test_fNDuvutBRXJLkQ', 'rzp_secret' => 'ObVJAj8L2e7V9RLOQkcdLtSw']);
        $date = date('Y-m-d H:m:i');
        $product = Product::create(['name' => 'Helpdesk Advance']);
        $user = User::factory()->create(['id' => mt_rand(1, 999), 'role' => 'user', 'country' => 'IN']);

        $invoice = Invoice::factory()->create(['user_id' => $user->id]);
        $invoiceItem = InvoiceItem::create(['invoice_id' => $invoice->id, 'product_name' => $product->name]);
        $order = Order::create(['client' => $user->id, 'order_status' => 'executed',
            'product' => 'Helpdesk Advance', 'number' => mt_rand(100000, 999999), 'invoice_id' => $invoice->id, ]);
        $permissions = 'No Permissions';
        $plan = Plan::create(['id' => 'mt_rand(1,99)', 'name' => 'Hepldesk 1 year', 'product' => $product->id, 'days' => 365]);
        $subscription = Subscription::create(['plan_id' => $plan->id, 'order_id' => $order->id, 'product_id' => $product->id, 'version' => 'v6.0.0', 'update_ends_at' => $date]);
        $response = (new CronController())->getOnDayExpiryInfoSubs();
        $this->assertEquals(0, $response->get()->count());
    }

    public function test_autoRenewal_return_payment_status_suucess()
    {
        $date = date('Y-m-d H:m:i');
        $product = Product::create(['name' => 'Helpdesk Advance']);
        $user = User::factory()->create(['id' => mt_rand(1, 999), 'role' => 'user', 'country' => 'IN']);

        $invoice = Invoice::factory()->create(['user_id' => $user->id]);
        $invoiceItem = InvoiceItem::create(['invoice_id' => $invoice->id, 'product_name' => $product->name]);
        $payment_method = 'stripe';
        $response = (new CronController())->postRazorpayPayment($invoice, $payment_method);
        $this->assertEquals('success', $response->payment_status);
    }
}
