<?php

namespace Tests\Unit;

use App\Model\Front\FrontendPage;
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
}
