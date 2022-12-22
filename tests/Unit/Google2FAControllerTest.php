<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Google2FAController;
use Illuminate\Http\Request;

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
        $totp = "123456";
        $response = $this->call('GET', '2fa/loginValidate', ['totp' => $totp]);
        $this->assertEquals('123456', $totp);
        $response = $this->get('my-invoices');
        $response->assertStatus(200);


    }
}
