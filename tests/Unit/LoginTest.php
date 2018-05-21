<?php

namespace Tests\Unit;

use Tests\DBTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class LoginTest extends DBTestCase
{
	use DatabaseTransactions;
   
     /** @group postLogin */
   public function test_postLogin_forVerifiedUsers()
    {
        $user = factory(User::class)->create();
        $response = $this->call('POST','login',['email1'=> $user->email , 'password1' => 'password']);
      $this->assertStringContainsSubstring($response->getTargetUrl(),'home');
        
    }

      /** @group postLogin */
   public function test_postLogin_forAdmin()
    {
        $user = factory(User::class)->create(['role'=>'admin']);
        $response = $this->call('POST','login',['email1'=> $user->email , 'password1' => 'password']);
        $this->assertStringContainsSubstring($response->getTargetUrl(),'/');
        
    }

}
