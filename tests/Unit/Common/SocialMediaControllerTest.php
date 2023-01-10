<?php

namespace Tests\Unit\Common;

use Tests\TestCase;
use App\User;
use App\Model\Common\SocialMedia;
class SocialMediaControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_socialmedia_saved_successfully()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
        $response = $this->post('/social-media', [
           'name' => 'youtube',
           'link' => 'https://youtube.com',      
         ]);
        $this->assertDatabaseHas('social_media',['name' => 'youtube']);
        $response->assertSessionHas('success','Saved Successfully');
    }

    public function test_validation_when_given_invalid_link()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
        $response = $this->post('/social-media', [
           'name' => 'youtube',
           'link' => 'https://youtub',      
         ]);
        $errors = session('errors');
       $response->assertStatus(302);
       $this->assertEquals($errors->get('link')[0], 'The link format is invalid.');

    }
    public function test_update_successfully()
    {
       $user = User::factory()->create(['role' => 'admin']);
       $this->actingAs($user);
       $social = SocialMedia::factory()->create(['name' => 'demo','link' => 'https://demo.com'])->id;
       $response = $this->post('/social-media', [
           'id' => $social,
           'name' => 'sgram',
           'link' => 'https://youtube.com',      
         ]);
        $this->assertDatabaseHas('social_media',['name' => 'sgram']);
        $response->assertSessionHas('success','Updated Successfully');
    }
}
