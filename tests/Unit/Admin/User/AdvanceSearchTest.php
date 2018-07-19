<?php

namespace Tests\Unit\Admin\User;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdvanceSearchTest extends TestCase
{
	use DatabaseTransactions;
    
    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForRole_whenNoUserWithThatRole()
    {
    	$this->withoutMiddleware();
        $user = factory(User::class,3)->create(['role'=>'admin']);
        $response = $this->call('GET','get-clients',[
        'role' => 'client',
        ]);
         $this->assertEquals(json_decode($response->content())->recordsTotal,0);

    }

     /** @group AdvanceSearch */
      public function test_get_client_advanceSearchForRole_whenUserRegisteredWithSelectedRole()
    {
    	$this->withoutMiddleware();
        $user = factory(User::class,3)->create(['role'=>'admin']);
        $response = $this->call('GET','get-clients',[
        'role' => 'admin',
        ]);
        $this->assertEquals(json_decode($response->content())->recordsTotal,3);

    }

     /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForPosition_whenUserRegisteredIsManager()
    {
    	$this->withoutMiddleware();
        $user = factory(User::class,3)->create(['position'=>'manager']);
        $response = $this->call('GET','get-clients',[
        'position' => 'manager',
        ]);
        $this->assertEquals(json_decode($response->content())->recordsTotal,3);

    }

    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForName_whenUserRegisteredHasSelectedName()
    {
    	$this->withoutMiddleware();
        $user = factory(User::class)->create(['first_name'=>'ashu']);
        $response = $this->call('GET','get-clients',[
        'first_name' => 'ashu',
        ]);
        $this->assertEquals(json_decode($response->content())->recordsTotal,1);

    }

    /** @group AdvanceSearch */
    public function test_get_client_advanceSearchForEmail_whenUserRegisteredHasSelectedEmail()
    {
    	$this->withoutMiddleware();
        $user = factory(User::class)->create(['email'=>'testmail..com']);
        $response = $this->call('GET','get-clients',[
        'email' => 'testmail..com',
        ]);
        $this->assertEquals(json_decode($response->content())->recordsTotal,1);

    }
}
