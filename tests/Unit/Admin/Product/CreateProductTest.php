<?php

namespace Tests\Unit\Admin\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;
use App\Model\Product\Product;

class CreateProductTest extends TestCase
{
	 use DatabaseTransactions;

   /* group ProductController */
    public function test_productController_storeProduct()
    {
    	$this->withoutMiddleware();
    	$product = factory(Product::class)->create();
    	$response = $this->call('POST','products',[
        'name' => 'helpdesk',
        'type' =>$product ->type,
        'group' =>$product->group,
        'category'=> $product->category,
        'require_domain' =>1,

    	]);
          // dd($response);
    	  $response->assertSessionHas('success');

        // $this->assertTrue(true);
    }

     public function test_productController_storeProductWithoutName()
    {
    	$this->withoutMiddleware();
    	$product = factory(Product::class)->create();
    	$response = $this->call('POST','products',[
         'type' =>$product ->type,
        'group' =>$product->group,
        'category'=> $product->category,
        'require_domain' =>1,

    	]);
    	
         $response->assertSessionHas('errors');
    }
}
