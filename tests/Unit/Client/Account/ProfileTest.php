<?php

namespace Tests\Unit\Client\Account;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class ProfileTest extends DBTestCase
{
    use DatabaseTransactions;

    /** @group my-profile */
    public function test_profile_whenClientUpdatesProfile()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $response = $this->call('PATCH', 'my-profile', [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email,
            'company'    => $user->company,
            'country'    => 'IN',
            'mobile_code'=> '91',
            'mobile'     => '9901541237',
            'address'    => $user->address,
            'town'       => $user->town,
            'timezone_id'=> '114',
            'state'      => $user->state,
            'zip'        => '560038',
            'profile_pic'=> '',
        ]);
        $response->assertStatus(302);
    }

    /** @group my-profile */
    public function test_profile_whenClientUpdatesPassword()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $response = $this->call('PATCH', 'my-password', [
            'old_password'    => $user->password,
            'new_password'    => 'Faveo@123',
            'confirm_password'=> 'Faveo@123',

        ]);
        $response->assertStatus(200);
    }

    /** @group my-profile */
    public function test_profile_whenOldAndNewPasswordDoesNotMatch()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $response = $this->call('PATCH', 'my-password', [
            'old_password'    => $user->password,
            'new_password'    => 'Faveo@12345',
            'confirm_password'=> 'Faveo@123',

        ]);
        $response->assertStatus(302);
    }
}
