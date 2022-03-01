<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class FreeTrailControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_firstloginatem_generateInvoiceOrder_returnstatus200()
    {

        $this->assertNotEquals(Auth::check(),'fails', \Lang::get('message.free-login'));
    
        $id = $this->user->id;
        $this->assertEquals($id,Auth::user()->id);
        $user_login = User::find($id);

        $this->assertNotEquals($user_login->first_time_login,0);
        $response->status(400);

        $this->assertDatabaseHas('users', ['id' => $id, 'first_time_login' => 1]);

         $invoice = $this->call('POST', url('api/generateFreetrailInvoice'));

         $invoice_items = $this->call('POST', url('api/createFreetrailInvoiceItems'));

         $order = $this->call('POST',url('api/executeFreetrailOrder'));

         $response->status(200);

    }
}
