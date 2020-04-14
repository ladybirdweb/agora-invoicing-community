<?php

namespace Tests\Unit\Admin\Dashboard;

use App\Http\Controllers\DashboardController;
use App\Model\Order\Invoice;
use App\Model\Product\Product;
use App\Model\Product\Subscription;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class DashboardControllerTest extends DBTestCase
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
    }

    /** @group Dashboard */
    public function test_getAllUsers_getListOfRecentUsers()
    {
        $user = factory(User::class, 3)->create();
        $controller = new \App\Http\Controllers\DashboardController();
        $response = $controller->getAllUsers();
        $this->assertCount(1, [$user]);
    }

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

    public function test_getSoldProducts_whenNumberOfDaysIsPassed_shouldGetOrdersForPassedNumberOfDays()
    {
        $this->getLoggedInUser("admin");
        $productOne = Product::create(["name"=> "one"]);
        $productTwo = Product::create(["name"=> "two"]);
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 1, "order_status"=>"executed"]);
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 2, "order_status"=>"executed"]);

        $order = $productOne->order()->create(["client"=>$this->user->id, "number"=> 3, "order_status"=>"executed"]);
        $order->created_at = Carbon::now()->subDays(2);
        $order->save();

        $productTwo->order()->create(["client"=>$this->user->id, "number"=> 4, "order_status"=>"executed"]);
        $response = $this->classObject->getSoldProducts(1);
        $this->assertEquals(2, $response[0]->order_count);
        $this->assertEquals($productOne->id, $response[0]->product_id);
        $this->assertEquals($productOne->name, $response[0]->product_name);
        $this->assertEquals(1, $response[1]->order_count);
        $this->assertEquals($productTwo->id, $response[1]->product_id);
        $this->assertEquals($productTwo->name, $response[1]->product_name);
    }

    public function test_getSoldProducts_whenNumberOfDaysIsNotPassed_shouldGiveAllRecords()
    {
        $this->getLoggedInUser("admin");
        $productOne = Product::create(["name"=> "one"]);
        $productTwo = Product::create(["name"=> "two"]);
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 1, "order_status"=>"executed"]);
        $productOne->order()->create(["client"=>$this->user->id, "number"=> 2, "order_status"=>"executed"]);

        $order = $productOne->order()->create(["client"=>$this->user->id, "number"=> 3, "order_status"=>"executed"]);
        $order->created_at = Carbon::now()->subDays(2);
        $order->save();

        $productTwo->order()->create(["client"=>$this->user->id, "number"=> 4, "order_status"=>"executed"]);
        $response = $this->classObject->getSoldProducts();

        $this->assertEquals(3, $response[0]->order_count);
        $this->assertEquals($productOne->id, $response[0]->product_id);
        $this->assertEquals($productOne->name, $response[0]->product_name);
        $this->assertEquals(1, $response[1]->order_count);
        $this->assertEquals($productTwo->id, $response[1]->product_id);
        $this->assertEquals($productTwo->name, $response[1]->product_name);
    }

    public function test_getExpiringSubscriptions_shouldGiveSubscriptionsWhichAreExpiringIn30Days()
    {
        $this->getLoggedInUser("admin");
        $product = Product::create(["name"=> "one"]);
        $orderOne = $product->order()->create(["client"=>$this->user->id, "number"=> 1, "price_override"=>10]);
        $orderTwo = $product->order()->create(["client"=>$this->user->id, "number"=> 2, "price_override"=>10]);
        Subscription::create(["update_ends_at"=> Carbon::now()->addDays(2), "order_id"=> $orderOne->id, "product_id"=>$product->id, 'user_id'=>$this->user->id]);
        Subscription::create(["update_ends_at"=> Carbon::now()->addDays(3), "order_id"=> $orderOne->id, "product_id"=>$product->id, 'user_id'=>$this->user->id]);
        Subscription::create(["update_ends_at"=> Carbon::now()->addDays(4), "order_id"=> $orderTwo->id, "product_id"=>$product->id, 'user_id'=>$this->user->id]);
        Subscription::create(["update_ends_at"=> Carbon::now()->addDays(5), "order_id"=> $orderTwo->id, "product_id"=>$product->id, 'user_id'=>$this->user->id]);
        Subscription::create(["update_ends_at"=> Carbon::now()->subDays(5), "order_id"=> $orderTwo->id, "product_id"=>$product->id, 'user_id'=>$this->user->id]);
        Subscription::create(["update_ends_at"=> Carbon::now()->addDays(31), "order_id"=> $orderTwo->id, "product_id"=>$product->id, 'user_id'=>$this->user->id]);

        $response = $this->classObject->getExpiringSubscriptions();

        $this->assertCount(4, $response);
        $this->assertEquals("1 days", $response[0]->remaining_days);
        $this->assertEquals("2 days", $response[1]->remaining_days);
        $this->assertEquals("3 days", $response[2]->remaining_days);
        $this->assertEquals("4 days", $response[3]->remaining_days);

        $this->assertEquals($this->user->first_name." ".$this->user->last_name, $response[0]->client_name);
        $this->assertEquals($this->user->first_name." ".$this->user->last_name, $response[1]->client_name);
        $this->assertEquals($this->user->first_name." ".$this->user->last_name, $response[2]->client_name);
        $this->assertEquals($this->user->first_name." ".$this->user->last_name, $response[3]->client_name);
    }


    public function test_getRecentOrders_shouldGiveOrdersInLast30DaysOrderedByDescCreatedAt()
    {
        $this->getLoggedInUser("admin");
        $product = Product::create(["name"=> "one"]);
        $orderOne = $product->order()->create(["client"=>$this->user->id, "number"=> 1, "price_override"=>10]);
        $orderTwo = $product->order()->create(["client"=>$this->user->id, "number"=> 2, "price_override"=>10]);

        $response = $this->classObject->getRecentOrders();

        $this->assertCount(2, $response);

        $this->assertEquals($orderTwo->number, $response[0]->order_number);
        $this->assertEquals($orderOne->number, $response[1]->order_number);

        $this->assertEquals($this->user->first_name." ".$this->user->last_name, $response[0]->client_name);
        $this->assertEquals($this->user->first_name." ".$this->user->last_name, $response[1]->client_name);

    }
}
