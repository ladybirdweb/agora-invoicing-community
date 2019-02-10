<?php

namespace Tests\Unit\Admin\Product;

use App\Model\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CreateProductTest extends TestCase
{
    use DatabaseTransactions;

    /** @group ProductController */
    public function test_productController_storeProduct()
    {
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $response = $this->call('POST', 'products', [
        'name'                 => 'helpdesk',
        'type'                 => $product->type,
        'group'                => $product->group,
        'product_sku'          => 'FAVEO-HD',
        'can_modify_agent'     => 1,
         'can_modify_quantity' => 1,
        'category'             => $product->category,
        'description'          => $product->description,
        'show_agent'           => 1,
        'require_domain'       => 1,

        ]);
        $response->assertSessionHas('success');

        // $this->assertTrue(true);
    }

    /** @group ProductController */
    public function test_productController_storeProductWithoutName()
    {
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $response = $this->call('POST', 'products', [
         'type'          => $product->type,
        'group'          => $product->group,
        'category'       => $product->category,
        'require_domain' => 1,

        ]);

        $response->assertSessionHas('errors');
    }
}
