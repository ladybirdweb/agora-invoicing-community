<?php

namespace Tests\Unit\Admin\User;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\DBTestCase;

class AdvanceSearchTest extends DBTestCase
{
    use DatabaseTransactions;

    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForRole_whenNoUserWithThatRole()
    {
        $this->withoutMiddleware();
        $user = factory(User::class, 3)->create(['role'=>'admin']);
        $response = $this->call('GET', 'get-clients', [
        'role' => 'client',
        ]);
        $this->assertEquals(json_decode($response->content())->recordsTotal, 0);
    }

    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForRole_whenUserRegisteredWithSelectedRole()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $user = $user->create(['role'=>'admin']);
        $response = $this->call('GET', 'get-clients', [
        'role' => 'admin',
        ]);
        $response->assertStatus(200);
        // $this->assertEquals(json_decode($response->content())->recordsTotal, 3);
    }

    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForPosition_whenUserRegisteredIsManager()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $user = $user->create(['position'=>'manager']);
        $response = $this->call('GET', 'get-clients', [
        'position' => 'manager',
        ]);
        $this->assertEquals(json_decode($response->content())->recordsTotal, 1);
    }

    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForName_whenUserRegisteredHasSelectedName()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $user = $user->create(['first_name'=>'ashu']);
        $response = $this->call('GET', 'get-clients', [
        'first_name' => 'ashu',
        ]);
        // dd($response);
        $response->assertStatus(200);
        // $this->assertEquals(json_decode($response->content())->recordsTotal, 1);
    }

    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForEmail_whenUserRegisteredHasSelectedEmail()
    {
        $this->withoutMiddleware();
        $this->getLoggedInUser();
        $user = $this->user;
        $user = $user->create(['email'=>'testmail.com']);
        $response = $this->call('GET', 'get-clients', [
        'email' => 'testmail.com',
        ]);
        $this->assertEquals(json_decode($response->content())->recordsTotal, 1);
    }
}
