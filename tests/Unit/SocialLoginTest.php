<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Tests\DBTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testSocialLoginHandler()
    {
        // Mock the Socialite driver
        $mockedUser = (object) [
            'email' => 'test@example.com',
            'nickname' => 'testuser',
            'name' => 'Test User',
        ];
        Socialite::shouldReceive('driver->user')->andReturn($mockedUser);

        // Set up the necessary data in the database
        $details = [
            'type' => 'provider',
            'redirect_url' => 'http://example.com/callback',
            'client_id' => 'client_id',
            'client_secret' => 'client_secret',
        ];
        SocialLogin::create($details);

        // Perform the request to the handler
        $response = $this->post('/login/provider');

        // Assert that the user was created or updated correctly
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'user_name' => 'testuser',
            'first_name' => 'Test User',
            'role' => 'user',
            'active' => '1',
        ]);

        // Assert that the redirect or verification response is returned
        $response->assertRedirect('/verify')->assertSessionHas('user')->assertStatus(302);
        //  $response->assertStatus(302);
    }

    public function testRequestOtp()
    {
        // Set up the necessary data in the database
        $user = User::factory()->create();
        $code = '123';
        $mobile = '123456789';
        $newNumber = '987654321';

        // Mock the sendOtp method
        $this->mockOtpSender(function ($mobileParam, $codeParam) use ($mobile, $code) {
            $this->assertEquals($mobile, $mobileParam);
            $this->assertEquals($code, $codeParam);
            return true; // Mocked successful response from sendOtp
        });

        // Perform the request to the requestOtp method
        $response = $this->post('/verify/request-otp', [
            'code' => $code,
            'mobile' => $mobile,
            'id' => $user->id,
            'newnumber' => $newNumber,
        ]);

        // Assert the response and verify that the mobile number was updated
        $response->assertJson([
            'type' => 'success',
            'message' => 'OTP has been sent to (+123) 123456789. Please Verify to Login',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'mobile' => $newNumber,
        ]);
    }

    private function mockOtpSender(\Closure $callback)
    {
        $mockedOtpSender = $this->getMockBuilder(OtpSender::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockedOtpSender->expects($this->once())
            ->method('sendOtp')
            ->will($this->returnCallback($callback));
        $this->app->instance(OtpSender::class, $mockedOtpSender);
    }
}


