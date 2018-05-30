<?php

namespace Tests\Unit\Admin\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;
use App\Model\Product\Product;
use App\Model\Github\Github;

class EditProductTest extends TestCase
{
	 use DatabaseTransactions;

    /* group ProductController */
    public function test_productController_updateProductDetails()
    {
    	$this->withoutMiddleware();
    	$product = factory(Product::class)->create();
    	$response = $this->call('PATCH','products/'.$product->id,[
        'name' => 'helpdesk',
        'type' =>$product ->type,
        'group' =>$product->group,
        'category'=> $product->category,
        'require_domain' =>1,
         'subscription' => 1

    	]);
    	// dd($response);
    	  $response->assertSessionHas('success');
    }
    
    


}
