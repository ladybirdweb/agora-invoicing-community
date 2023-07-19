<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class SocialLoginTest extends DBTestCase
{
    use DatabaseTransactions;

    public function login_register_testwithemail()
    {
        $user = User::factory()->create();
        $response = $this->call('GET', '/auth/redirect/google',
            [
                'email' => $user->email,
                'role' => 'user',
                'active' =>1,
            ]);
        $response->assertStatus(302);
    }

    public function test_social_foradmin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->call('GET', 'auth/redirect/{provider}',
            ['email' => $user->email,
            ]);
        $response->assertStatus(302);
    }

    public function test_sociallogin_after_register()
    {
        $user = User::factory()->create(['mobile_verified' => 0]);
        $response = $this->call('GET', 'auth/redirect/{provider}',
            [
                'email' => $user->email,
                'active' => 1,
                'mobile_verified' => 0,
            ]);
        $response->assertStatus(302);
    }

    public function test_socialloginwhen_mobile_verifiedstatusis_verified()
    {
        $user = User::factory()->create(['mobile_verified' => 1]);
        $response = $this->call('GET', 'auth/redirect/{provider}',
            [
                'email' => $user->email,
                'active' => 1,
                'mobile_verified' => 1,
            ]);
        $response->assertStatus(302);
    }

    public function test_for_basic_details()
    {
        $user = User::factory()->create();
        $response = $this->call('POST', 'store-basic-details', [
            'country' => $user->country,
            'address' => $user->address,
        ]);
        $response->assertStatus(302);
    }

    public function test_updatecredentials()
    {
        $user = User::factory()->create();
        $response = $this->call('POST', 'update-social-login',
            [
                'id' => $user->id,
                'status' => '1',
            ]);
        $response->assertStatus(302);
    }
}
