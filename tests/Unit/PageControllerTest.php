<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_createpage_returnstatus200()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);

        $response = $this->post('/pages', [
            'name'=>'demo',
            'slug'=> 'demopass',
            'url' => 'http://demo.com',
            'publish' => 'yes',
            'content' => 'Here the new page created',
        ]);
        $this->assertDatabaseHas('frontend_pages', ['slug'=>'contact-us']);
    }
}
