<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class LoginTest extends DBTestCase
{
    use DatabaseTransactions;

    /** @group postLogin */
    public function test_postLogin_forVerifiedUsers()
    {
        $user = factory(User::class)->create();
        $response = $this->call('POST', 'login', ['email1'=> $user->email, 'password1' => 'password']);
        $response->assertStatus(302);
        // $this->assertStringContainsSubstring($response->getTargetUrl(), 'home');
    }

    /** @group postLogin */
    public function test_postLogin_forAdmin()
    {
        $user = factory(User::class)->create(['role'=>'admin']);
        $response = $this->call('POST', 'login', ['email1'=> $user->email, 'password1' => 'password']);
        $this->assertStringContainsSubstring($response->getTargetUrl(), '/');
    }

    /** @group postLogin */
    public function test_postLogin_when_mobile_is_Unverified()
    {
        $user = factory(User::class)->create(['mobile_verified'=>0]);
        $response = $this->call('POST', 'login', ['email1'=>$user->email, 'password1' => 'password']);
        $response->assertStatus(302);
        // $this->assertStringContainsSubstring($response->getTargetUrl(), '/verify');
    }

    /** @group postLogin */
    public function test_postLogin_when_email_is_Unverified()
    {
        $user = factory(User::class)->create(['active'=>0]);
        $response = $this->call('POST', 'login', ['email1'=>$user->email, 'password1' => 'password']);
        $response->assertStatus(302);
    }

    /** @group postLogin */
    public function test_postLogin_when_email_and_mobile_are_Unverified()
    {
        $user = factory(User::class)->create(['active'=>0, 'mobile_verified'=>0]);
        $response = $this->call('POST', 'login', ['email1'=>$user->email, 'password1' => 'password']);
        $response->assertStatus(302);
        // $this->assertStringContainsSubstring($response->getTargetUrl(), '/verify');
    }
}
