<?php

namespace Tests\Unit\Admin\Dashboard;

use App\Http\Controllers\DashboardController;
use App\Model\Order\Invoice;
use App\Model\Product\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class DashboardTest extends DBTestCase
{
    use DatabaseTransactions;

    private $classObject;

    public function setUp(): void
    {
        parent::setUp();

        $this->classObject = new DashboardController();
    }

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
    public function test_getRecentOrders_getsRecentlySoldProductInLast30DaysWithCorrespondingCount()
    {
        $this->getLoggedInUser("admin");
        $productOne = Product::create(["name"=> "one"]);
        $productTwo = Product::create(["name"=> "two"]);
        $orderOne = $productOne->order()->create(["client"=>$this->user->id, "number"=> 1, "price_override"=>10]);
        $orderTwo = $productOne->order()->create(["client"=>$this->user->id, "number"=> 2, "price_override"=>20]);

        // creating one without price override
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 3]);
        $orderFour = $productTwo->order()->create(["client"=>$this->user->id, "number"=> 4, "price_override"=>10]);
        $response = $this->classObject->getRecentOrders();

        $this->assertCount(3, $response);

        $this->assertEquals($orderFour->number, $response[0]->order_number);
        $this->assertEquals($productTwo->name, $response[0]->product_name);
        $this->assertEquals($this->user->first_name . " ". $this->user->last_name, $response[0]->client_name);

        $this->assertEquals($orderTwo->number, $response[1]->order_number);
        $this->assertEquals($productOne->name, $response[1]->product_name);
        $this->assertEquals($this->user->first_name . " ". $this->user->last_name, $response[1]->client_name);

        $this->assertEquals($orderOne->number, $response[2]->order_number);
        $this->assertEquals($productOne->name, $response[2]->product_name);
        $this->assertEquals($this->user->first_name . " ". $this->user->last_name, $response[2]->client_name);
    }

    public function test_getRecentProductSold_getsRecentlySoldProductInLast30DaysWithCorrespondingCount()
    {
        $this->getLoggedInUser("admin");
        $productOne = Product::create(["name"=> "one"]);
        $productTwo = Product::create(["name"=> "two"]);
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 1, "order_status"=>"executed"]);
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 2, "order_status"=>"executed"]);
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 3]);
        $productTwo->order()->create(["client"=>$this->user->id, "number"=> 4, "order_status"=>"executed"]);
        $response = $this->classObject->getRecentProductSold();
        $this->assertEquals(2, $response[0]->order_count);
        $this->assertEquals($productOne->id, $response[0]->product_id);
        $this->assertEquals($productOne->name, $response[0]->product_name);
        $this->assertEquals(1, $response[1]->order_count);
        $this->assertEquals($productTwo->id, $response[1]->product_id);
        $this->assertEquals($productTwo->name, $response[1]->product_name);
    }
}
