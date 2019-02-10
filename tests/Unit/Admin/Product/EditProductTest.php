<?php

namespace Tests\Unit\Admin\Product;

use App\Model\Product\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EditProductTest extends TestCase
{
    use DatabaseTransactions;

    /* group ProductController */
    public function test_productController_updateProductDetails()
    {
        $this->withoutMiddleware();
        $product = factory(Product::class)->create();
        $response = $this->call('PATCH', 'products/'.$product->id, [
        'name'                => 'helpdesk',
        'type'                => 1,
        'group'               => $product->group,
        'product_sku'         => 'FAVEO-HD',
        'category'            => $product->category,
        'description'         => $product->description,
        'can_modify_agent'    => 0,
        'can_modify_quantity' => 0,
        'show_agent'          => 0,
        'require_domain'      => 1,
         'subscription'       => 1,

        ]);
        $response->assertSessionHas('success');
    }
}
