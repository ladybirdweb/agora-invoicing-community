<?php
namespace Tests\Unit\AutoUpdate;

use App\Http\Controllers\AutoUpdate\PlanUpgradeController;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Product\Product;
use Tests\DBTestCase;
class PlanUpgradeControllerTest extends DBTestCase
{
    public function test_basicProductCrud_returnIfDatabaseHasThatValue(){
        $this->getLoggedInUser();
        $this->withoutMiddleware();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product3 = Product::factory()->create();

        $data = [
            'upgradeOrDowngrade' => array($product2->id,$product3->id),
            'oldProduct' => $product1->id,
        ];
        $this->json('POST', url('UpgradeSettingSave'), $data);
        $this->assertDatabaseHas('upgrade_settings',['upgrade_product_id'=>$product3->id]);
        $this->assertDatabaseHas('upgrade_settings',['upgrade_product_id'=>$product2->id]);
        $this->assertDatabaseHas('upgrade_settings',['product_id'=>$product1->id]);

    }

    public function test_CheckWhatIsThePrice_returnTheUpgradePrice(){
        $classObj = new PlanUpgradeController();
        $price = $this->getPrivateMethod($classObj,'decideThePrice',[100000,42200,"2023-10-19",365]);
        $this->assertEquals(32800,$price);
    }

    public function test_profofodfo(){
        $product = Product::factory()->create();
        $plan = Plan::factory()->create(['product'=>$product->id]);
        PlanPrice::factory()->create(['product_quantity'=>1,'no_of_agents'=>1,'plan_id'=> $plan->id]);
        $classObj = new PlanUpgradeController();
        $price = $this->getPrivateMethod($classObj,'addUpgradeProduct',[$product->id,10000]);
        $this->assertEquals('10000',$price['price']);
    }
}