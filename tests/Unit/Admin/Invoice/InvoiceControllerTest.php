<?php

namespace Tests\Unit\Admin\Invoice;

use App\Http\Controllers\Order\InvoiceController;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\DBTestCase;

class InvoiceControllerTest extends DBTestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->classObject = new InvoiceController();
    }

    /** @group invoice */
    public function test_generateInvoice_generatesInvoiceAndInvoiceItem()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $taxCondition = new \Darryldecode\Cart\CartCondition([
            'name'   => 'GST', 'type'   => 'tax',
            'value'  => 5,
        ]);
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => [],
            'conditions' => $taxCondition,
        ]);
        $invoice = $this->classObject->generateInvoice();
        $this->assertDatabaseHas('invoices', ['id' => $invoice->id]);
    }

    /** @group invoice */
    public function test_createInvoiceItems_createsNewInvoiceItem()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $plan = Plan::create(['name'=>'Hepldesk 1 year', 'product'=>$product->id, 'days'=>365]);
        $taxCondition = new \Darryldecode\Cart\CartCondition([
            'name'   => 'GST', 'type'   => 'tax',
            'value'  => 5,
        ]);
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => [],
            'conditions' => $taxCondition,
        ]);
        $invoice = $this->classObject->generateInvoice();
        foreach (\Cart::getContent() as $cart) {
            $invoiceItem = $this->classObject->createInvoiceItems($invoice->id, $cart);
            $this->assertDatabaseHas('invoice_items', ['invoice_id' => $invoice->id]);
        }
    }

    /** @group invoice */
    public function test_invoiceGenerateByForm_createsNewInvoice()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $plan = Plan::create(['name'=>'Hepldesk 1 year', 'product'=>$product->id, 'days'=>365]);
        $planPrice = PlanPrice::create(['plan_id'=>$plan->id, 'currency'=>$this->user->currency, 'add_price'=>'1000', 'renew_price'=>'500', 'product_quantity'=>1, 'no_of_agents'=>0]);

        $invoice = $this->classObject->invoiceGenerateByForm(new Request(['user'=>$this->user->id, 'date'=>'09/16/2020', 'product'=>$product->id, 'price'=>$planPrice->add_price, 'code'=>'', 'quantity'=>$planPrice->product_quantity, 'plan'=>$plan->id, 'subscription'=> true, 'description'=>'']));
        $message = json_decode($invoice->getContent())->message->success;
        $this->assertEquals($message, 'Invoice generated successfully');
    }
}
