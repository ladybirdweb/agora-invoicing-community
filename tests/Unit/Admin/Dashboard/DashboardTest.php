<?php

namespace Tests\Unit\Admin\Dashboard;

use App\Model\Order\Invoice;
use App\Model\Order\InvoiceItem;
use App\Model\Order\Order;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class DashboardTest extends DBTestCase
{
    use DatabaseTransactions;

    /** @group Dashboard */
    public function test_getTotalSalesInInr_gettingTotalSalesInr()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $invoice = factory(Invoice::class)->create(['user_id'=>$user->id]);
        $controller = new \App\Http\Controllers\DashboardController();
        $allowedCurrencies2 = 'INR';
        $response = $controller->getTotalSalesInCur2($allowedCurrencies2);
        $this->assertEquals($response, '10000');
    }

    /** @group Dashboard */
    public function test_getYearlySalesInInr_gettingYearlySalesInr()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $invoice = factory(Invoice::class, 3)->create(['user_id'=>$user->id]);
        $controller = new \App\Http\Controllers\DashboardController();
        $allowedCurrencies2 = 'INR';
        $response = $controller->getYearlySalesCur2($allowedCurrencies2);
        $this->assertEquals($response, '30000');

        // dd($response);
    }

    /** @group Dashboard */
    public function test_getYearlySalesInInr_whenInvoiceTotalIsFromPreviousYear()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $invoice = factory(Invoice::class, 3)->create(['created_at'=>2017, 'user_id'=>$user->id]);
        $controller = new \App\Http\Controllers\DashboardController();
        $allowedCurrencies2 = 'INR';
        $response = $controller->getYearlySalesCur2($allowedCurrencies2);
        $this->assertEquals($response, '0');

        // dd($response);
    }

    /** @group Dashboard */
    // public function test_getMonthlySalesInInr_getMonthlySales()
    // {
    //     $this->withoutMiddleware();
    //     $this->getLoggedInUser();
    //     $user = $this->user;
    //     $invoice = factory(Invoice::class, 3)->create(['user_id'=>$user->id]);
    //     $controller = new \App\Http\Controllers\DashboardController();
    //     $response = $controller->getYearlySalesInInr();
    //     $this->assertEquals($response, '30000');
    // }

    /** @group Dashboard */
    public function test_getAllUsers_getListOfRecentUsers()
    {
        $user = factory(User::class, 3)->create();
        $controller = new \App\Http\Controllers\DashboardController();
        $response = $controller->getAllUsers();
        $this->assertCount(1, [$user]);
    }

    /** @group Dashboard */
    public function test_recentProductSold_getProductsRecentlySold()
    {
        $this->getLoggedInUser();
        $user = $this->user;
        $invoice = factory(Invoice::class)->create(['user_id'=>$user->id]);
        $invoiceItem = InvoiceItem::create([
            'invoice_id'=> $invoice->id,
        ]);
        $order = factory(Order::class)->create(['invoice_id'=> $invoice->id, 'invoice_item_id'=>$invoiceItem->id,
            'client'                                        => $user->id, ]);
        $controller = new \App\Http\Controllers\DashboardController();
        $response = $controller->recentProductSold();
        $this->assertCount(1, [$order]);
    }
}
