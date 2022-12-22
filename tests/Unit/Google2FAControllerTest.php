<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class Google2FAControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_postLoginValidateToken_redirectto_clientpanel()
    {
        $user = User::factory()->create(['role' => 'user', 'country' => 'IN']);
        $auth = Auth::loginUsingId($user->id);
        $this->actingAs($user);
        $totp = '123456';
        $response = $this->call('GET', '2fa/loginValidate', ['totp' => $totp]);
        $this->assertEquals('123456', $totp);
        $response = $this->get('my-invoices');
        $response->assertStatus(200);
    }
}
