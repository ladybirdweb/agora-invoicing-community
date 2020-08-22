<?php

namespace Tests\Unit\Client\Cart;

use App\Http\Controllers\Front\BaseCartController;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Tests\DBTestCase;

class BaseCartControllerTest extends DBTestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        $this->classObject = new BaseCartController();
    }

    /** @group quantity */
    public function test_getCartValues_calculatesAgentQtyPriceOfCartWhenReducingAgtAllowed_returnArrayToBeAdded()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $response = $this->getPrivateMethod($this->classObject, 'getCartValues', [$product->id, true]);
        $this->assertEquals($response['agtqty'], 5); //Reduced to half
        $this->assertEquals($response['price'], 500); //Reduced to half
    }

    /** @group quantity */
    public function test_getCartValues_calculateAgentQtyPriceOfCartWhenIncreasinAgtAllowed_returnArrayToBeAdded()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $response = $this->getPrivateMethod($this->classObject, 'getCartValues', [$product->id]);
        $this->assertEquals($response['agtqty'], 20); //Doubled
        $this->assertEquals($response['price'], 2000); //Doubled
    }

    /** @group quantity */
    public function test_getCartValues_calculateAgentQtyPriceOfCartWhenInvalidProductPassed_throwsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not present in cart.');
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create(['name'=>'SD Enterprise']);
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product1->id,
            'name'       => $product1->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $response = $this->getPrivateMethod($this->classObject, 'getCartValues', [$product2->id]);
    }

    /** @group quantity */
    public function test_updateAgentQty_updatesCart_returnsUpdatedCart()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create(['can_modify_agent'=>1]);
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $response = $this->call('POST', 'update-agent-qty', [
            'productid' => $product->id,
        ]);
        foreach (\Cart::getContent() as $cart) {
            $this->assertEquals($cart->price, 2000);
            $this->assertEquals($cart->attributes->agents, 20);
        }
    }

    /** @group quantity */
    public function test_updateAgentQty_updatesCartWhenModifyingAgentNotAllowed_returnsSameCartValues()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $response = $this->call('POST', 'update-agent-qty', [
            'productid' => $product->id,
        ]);
        foreach (\Cart::getContent() as $cart) {
            $this->assertEquals($cart->price, 1000);
            $this->assertEquals($cart->attributes->agents, 10);
        }
    }

    /** @group quantity */
    public function test_updateProductQty_updatesCartWhenModifyingQtyAllowed_returnsUpdatedCart()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create(['can_modify_quantity'=>1]);
        $plan = Plan::create(['name'=>'HD Plan 1 year', 'product'=>$product->id, 'days'=>366]);
        $planPrice = PlanPrice::create(['plan_id'=>$plan->id, 'currency'=>'INR', 'add_price'=>'1000', 'renew_price'=>'500', 'price_description'=> 'Random description', 'product_quantity'=>1, 'no_of_agents'=>0]);
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $response = $this->call('POST', 'update-qty', [
            'productid' => $product->id,
        ]);
        foreach (\Cart::getContent() as $cart) {
            $this->assertEquals($cart->quantity, 2);
        }
    }

    /** @group quantity */
    public function test_updateProductQty_updatesCartWhenModifyingQtyNotAllowed_throwsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot Modify Quantity');
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $plan = Plan::create(['name'=>'HD Plan 1 year', 'product'=>$product->id, 'days'=>366]);
        $planPrice = PlanPrice::create(['plan_id'=>$plan->id, 'currency'=>'INR', 'add_price'=>'1000', 'renew_price'=>'500', 'price_description'=> 'Random description', 'product_quantity'=>1, 'no_of_agents'=>0]);
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 1,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $this->classObject->updateProductQty(new Request(['productid'=>$product->id]));
    }

    /** @group quantity */
    public function test_reduceProductQty_reduceCartQtyWhenModifyingQtyAllowed_returnsUpdatedCart()
    {
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create(['can_modify_quantity'=>1]);
        $plan = Plan::create(['name'=>'HD Plan 1 year', 'product'=>$product->id, 'days'=>366]);
        $planPrice = PlanPrice::create(['plan_id'=>$plan->id, 'currency'=>'INR', 'add_price'=>'1000', 'renew_price'=>'500', 'price_description'=> 'Random description', 'product_quantity'=>1, 'no_of_agents'=>0]);
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 2,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $response = $this->call('POST', 'reduce-product-qty', [
            'productid' => $product->id,
        ]);
        foreach (\Cart::getContent() as $cart) {
            $this->assertEquals($cart->quantity, 1);
        }
    }

    /** @group quantity */
    public function test_reduceProductQty_updatesCartWhenModifyingQtyNotAllowed_throwsException()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot Modify Quantity');
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $plan = Plan::create(['name'=>'HD Plan 1 year', 'product'=>$product->id, 'days'=>366]);
        $planPrice = PlanPrice::create(['plan_id'=>$plan->id, 'currency'=>'INR', 'add_price'=>'1000', 'renew_price'=>'500', 'price_description'=> 'Random description', 'product_quantity'=>1, 'no_of_agents'=>0]);
        $currency = userCurrency();
        \Cart::add([
            'id'         => $product->id,
            'name'       => $product->name,
            'price'      => 1000,
            'quantity'   => 2,
            'attributes' => ['currency' => $currency, 'symbol'=> $currency, 'agents'=> 10],
        ]);
        $this->classObject->reduceProductQty(new Request(['productid'=>$product->id]));
    }
}
