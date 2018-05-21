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
        $this->assertStringContainsSubstring($response->getTargetUrl(), 'home');
    }

    /** @group postLogin */
    public function test_postLogin_forAdmin()
    {
        $user = factory(User::class)->create(['role'=>'admin']);
        $response = $this->call('POST', 'login', ['email1'=> $user->email, 'password1' => 'password']);
        $this->assertStringContainsSubstring($response->getTargetUrl(), '/');
    }
}
