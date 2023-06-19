<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SocialLoginTest extends TestCase
{
    use DatabaseTransactions;

    public function testSocialLoginHandler()
    {
        $user = User::factory()->create();
        $response = $this->call('POST', '/auth/redirect/{github}', [

            'email' =>Str::random(10).gmail.com,
            'country' => 'India',
            'mobile_code' => '91',
            'mobile' => $faker->phoneNumber,
            'address' => $user->address,
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'country' => 'India',
            'mobile_code' => '91',
        ]);
    }
}
