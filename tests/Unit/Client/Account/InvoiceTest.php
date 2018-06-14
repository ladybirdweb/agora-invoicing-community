<?php

namespace Tests\Unit\Client\Account;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
	use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */

     private function invoiceItem($invoice)
    {
        $params = [
            'invoice_id'       => $invoice->id,
            'product_name'       => 'Helpdesk Advance',
            'regular_price'    => 10000,
            'quantity'    => 1,
            'tax_name'   => 'CGST+SGST',
            'tax_percentage'  => 18,
            'subtotal'=> 11800,
            'domain'   => 'faveo.com',
            'plan_id'  => 1 
                ];
        $this->call('post', 'plans', $params);


    public function testInvoices()
    {
    	$this->withuuMiddleware();
    	$invoice = factory(Invoice::class)->create();
    	$invoiceItem = InvoiceItem::create ([
            'invoice_id'       => $invoice->id,
            'product_name'       => 'Helpdesk Advance',
            'regular_price'    => 10000,
            'quantity'    => 1,
            'tax_name'   => 'CGST+SGST',
            'tax_percentage'  => 18,
            'subtotal'=> 11800,
            'domain'   => 'faveo.com',
            'plan_id'  => 1 
                ]_;
        $response = $this->call('PATCH', 'products/'.$product->id, [

        ]);
    }
}
