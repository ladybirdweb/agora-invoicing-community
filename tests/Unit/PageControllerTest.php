<?php

namespace Tests\Unit;

use App\Http\Controllers\Front\PageController;
use App\Model\Front\FrontendPage;
use App\Model\Payment\Plan;
use App\Model\Product\Product;
use App\User;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_validation_fails_if_required_field_empty()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
        $response = $this->post('/pages', [
            'name'=>'demo',
            'slug'=> 'demopass',
            'url' => 'http://demo.com',
            'content' => 'Here the new page created',
        ]);
        $errors = session('errors');
        $response->assertStatus(302);
    }

    public function test_updatepage_returnstatus200()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
        $page = FrontendPage::create(['name'=>'demo',
            'slug'=> 'demopass',
            'url' => 'http://demo.com',
            'publish' => 'yes',
            'content' => 'Here the new page created', ]);

        $response = $this->post('/pages', [
            'id'=> $page->id,
            'name'=>'demo',
            'slug'=> 'demopass',
            'url' => 'http://demo.com',
            'publish' => 'yes',
            'content' => 'Here the new page created',
        ]);
        $this->assertDatabaseHas('frontend_pages', ['name'=>'demo']);
    }

    public function test_plansYear_returnstatus200()
    {
        // Create a sample product
        $product = Product::factory()->create([
            'id' => 1,
            'name' => 'Example Product',
        ]);

        // Call the plansYear method with the sample URL and product ID
        $page = new PageController();
        $form = $page->plansYear('/example-url', $product->id);

        // Assert that the form contains the expected action URL
        $expectedAction = 'http://localhost/example-url';
        $this->assertStringContainsString('action="'.$expectedAction.'"', $form);
    }

    public function test_getPrice()
    {
        $product = Product::factory()->create([
            'id' => 1,
            'name' => 'Example Product',
        ]);
        $months = 12;
        $price = [];
        $priceDescription = 'per year';
        $value = Plan::factory()->create([
            'product' => $product->id,
            'days' => 30,
        ]);
        $value->id = 1;
        $cost = 100;
        $currency = 'USD';
        $offer = 10;

        $page = new PageController();
        $result = $page->getPrice($months, $price, $priceDescription, $value, $cost, $currency, $offer, $product);

        $expectedPrice = '12  $1,080.00 per year';
        $this->assertEquals($expectedPrice, $result[$value->id]);
    }
}
