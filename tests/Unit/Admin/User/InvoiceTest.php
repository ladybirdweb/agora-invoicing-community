<?php

namespace Tests\Unit\Admin\User;

use App\Model\Order\Invoice;
use App\Model\Payment\Currency;
use App\Model\Payment\Plan;
use App\Model\Payment\PlanPrice;
use App\Model\Payment\PromoProductRelation;
use App\Model\Payment\Promotion;
use App\Model\Payment\PromotionType;
use App\Model\Payment\Tax;
use App\Model\Payment\TaxClass;
use App\Model\Payment\TaxOption;
use App\Model\Payment\TaxProductRelation;
use App\Model\Product\Product;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class InvoiceTest extends DBTestCase
{
    use DatabaseTransactions;

    private function currencyCreate()
    {
        $params = [
            'code'           => 'INR',
            'symbol'         => 'Rs',
            'name'           => 'indian rupee',
            'base_conversion'=> '1.0',
        ];
        $this->call('post', 'currency', $params);
    }

    private function planPrices($plan)
    {
        $params = [
            'name'       => $plan->name,
            'days'       => $plan->days,
            'product'    => $plan->product,
            'plan_id'    => $plan->id,
            'currency'   => ['USD', 'INR'],
            'add_price'  => ['USD'=>'12', 'INR'=>'1256'],
            'renew_price'=> ['USD'=>'12', 'INR'=>'1256'],
                ];
        $this->call('post', 'plans', $params);

        return $params;
    }

    /** @group InvoiceController */
    public function test_invoiceController_visitingCreateInvoicePage()
    {
        $this->withoutMiddleware();
        $this->currencyCreate();
        $client_id = factory(User::class)->create(['role' => 'user'])->id;
        $product = factory(Product::class)->create();
        $response = $this->call('GET', 'invoice/generate?clientid='.$client_id, [

        'product' => $product->name,

        ]);
        $this->assertStringContainsSubstring($response->content(), 'Whoops');
    }

    /** @group InvoiceController */
    public function test_invoiceController_generateInvoiceWithoutPromoCode()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $planPrice = $this->planPrices($plan);
        $selectedprize = ($user->currency == 'INR') ? $planPrice['add_price']['INR'] : $planPrice['add_price']['USD'];
        $response = $this->call('POST', 'generate/invoice/'.$user->id, [
           'product'      => $product->id,
           'price'        => $selectedprize,
           'code'         => '',
           'domain'       => 'localhost',
           'plan'         => $plan->id,
           'subscription' => true,
           'description'  => '',

           ]);
        $response->assertStatus(200);
        // $this->assertEquals(json_decode($response->content())->result->success, 'Invoice generated successfully');
    }

    /** @group InvoiceController */
    public function test_invoiceController_checkTaxWhenGstIsDisable_whenUserIsFromIndia_taxIsForAnyCountry()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR', 'state'=> 'IN-KA', 'country'=>'IN']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Others']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 1,
        'name'          => 'Global Tax',
        'country'       => '',
        'state'         => 'Any State',
        'rate'          => '50', ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);
        // dd($response)
        $this->assertEquals($response[0], 'Global Tax');
        $this->assertEquals($response[1], '50');
    }

    /** @group InvoiceController */
    public function test_invoiceController_checkTaxWhenGstIsDisable_whenUserIsFromIndia()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR', 'state'=> 'IN-KA', 'country'=>'IN']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Others']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 1,
        'name'          => 'Intra State GST',
        'country'       => 'IN',
        'state'         => 'IN-KA',
        'rate'          => '20', ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);

        $this->assertEquals($response[0], 'null');
        $this->assertEquals($response[1], '0');
    }

    /** @group InvoiceController */
    public function test_invoiceController_generateInvoiceWhenGstIsEnable_whenUserIsFromIndiaFromSameState()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR', 'state'=> 'IN-KA', 'country'=>'IN']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $rule = TaxOption::where('id', 1)->update(['tax_enable'=>'1']);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Intra State GST']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 1,
        'name'          => 'Intra State GST',
        'country'       => 'IN',
        'state'         => 'IN-KA',
        ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);

        $this->assertEquals($response[0], 'Intra State GST');
        $this->assertEquals($response[1], '18%');
    }

    /** @group InvoiceController */
    public function test_invoiceController_generateInvoiceWhenGstIsEnable_whenUserIsFromIndiaFromOtherState()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR', 'state'=> 'IN-MH', 'country'=>'IN']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $rule = TaxOption::where('id', 1)->update(['tax_enable'=>'1']);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Inter State GST']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 1,
        'name'          => 'Inter State GST',
        'country'       => 'IN',
        'state'         => 'IN-MH',
        ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);

        $this->assertEquals($response[0], 'Inter State GST');
        $this->assertEquals($response[1], '18%');
    }

    /** @group InvoiceController */
    public function test_invoiceController_checkTaxWhenGstIsEnable_whenUserIsFromIndiaFromUnionTerritory()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR', 'state'=> 'IN-AN', 'country'=>'IN']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $rule = TaxOption::where('id', 1)->update(['tax_enable'=>'1']);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Union Territory GST']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 1,
        'name'          => 'Union Territory GST',
        'country'       => 'IN',
        'state'         => 'IN-MH',
        ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);

        $this->assertEquals($response[0], 'Union Territory GST');
        $this->assertEquals($response[1], '18%');
    }

    /** @group InvoiceController */
    public function test_checkTax_WhenGstIsEnable_whenUserIsFromOtherCountry()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'USD', 'state'=> 'AU-QLD', 'country'=>'AU']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $rule = TaxOption::where('id', 1)->update(['tax_enable'=>'1']);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Australian Tax']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 1,
        'name'          => 'Australian Tax',
        'country'       => 'AU',
        'state'         => 'AU-QLD',
        'rate'          => '20',
        ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);
        $this->assertEquals($response[0], 'Australian Tax');
        $this->assertEquals($response[1], '20%');
    }

    /** @group InvoiceController */
    public function test_checkTax_globalTax_whenUserIsFromOtherCountry()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'USD', 'state'=> 'AU-QLD', 'country'=>'AU']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $rule = TaxOption::where('id', 1)->update(['tax_enable'=>'1']);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Australian Tax']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 1,
        'name'          => 'Global Tax',
        'country'       => '',
        'state'         => 'Any State',
        'rate'          => '20',
        ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);
        // dd($response);
        $this->assertEquals($response[0], 'Global Tax');
        $this->assertEquals($response[1], '20%');
    }

    /** @group InvoiceController */
    public function test_checkTax_whenGstIsEnable_statusIsInactive()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR', 'state'=> 'IN-MH', 'country'=>'IN']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $rule = TaxOption::where('id', 1)->update(['tax_enable'=>'1']);
        $planPrice = $this->planPrices($plan);
        $taxClass = TaxClass::create(['name'=>'Inter State GST']);
        $tax = Tax::create([
        'tax_classes_id'=> $taxClass->id,
        'active'        => 0,
        'name'          => 'Inter State GST',
        'country'       => 'IN',
        'state'         => 'IN-MH',
        ]);

        $taxProductRelation = TaxProductRelation::create([
        'product_id'    => $product->id,
         'tax_class_id' => $taxClass->id,
         ]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkTax($product->id, $user->id);
        // dd($response);
        $this->assertEquals($response[0], 'Inter State GST');
        $this->assertEquals($response[1], '0');
    }

    /** @group InvoiceController */
    public function test_checkCode_whenPromoCodeIsApplied()
    {
        $this->withoutMiddleware();
        $user = factory(User::class)->create(['currency'=> 'INR', 'state'=> 'IN-MH', 'country'=>'IN']);
        $product = factory(Product::class)->create();
        $plan = factory(Plan::class)->create(['product'=>$product->id]);
        $planPrice = $this->planPrices($plan);
        // $planPr= PlanPrice::orderBy('id','ASC')->first();
        // dd($planPrice,$plan->id);
        $promotionType = PromotionType::create(['name'=>'Fixed Amount']);

        $promotion = Promotion::create([
        'code'   => 'abcd111',
        'type'   => $promotionType->id,
        'uses'   => '1',
        'value'  => '100',
        'start'  => '2017-09-09',
        'expiry' => '2018-09-09',
        ]);
        $promoProductRelation = PromoProductRelation::create(['promotion_id'=>$promotion->id, 'product_id'=>$product->id]);

        $controller = new \App\Http\Controllers\Order\InvoiceController();
        $response = $controller->checkcode($promotion->code, $product->id, $user->currency);

        $this->assertEquals($response, null);
    }
}
