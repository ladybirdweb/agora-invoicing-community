<?php

namespace Tests\Unit\Admin\User;

use App\User;
use Illuminate\Http\Request;
use Tests\DBTestCase;
use App\Http\Controllers\User\ClientController;

class ClientControllerTest extends DBTestCase
{
    private $classObject;

    public function setUp(): void
    {
        parent::setUp();
        $this->classObject = new ClientController();
    }

    public function test_getBaseQueryForUserSearch_whenCountryIsPresentInTheRequest_filtersResultByThatCountry()
    {
        $request = new Request(['country'=> "US"]);
        factory(User::class)->create(['country'=>'US']);
        factory(User::class)->create(['country'=>'IN']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals("UNITED STATES", $users->first()->country);
    }

    public function test_getBaseQueryForUserSearch_whenIndustryIsPresentInTheRequest_filtersResultByThatIndustry()
    {
        $request = new Request(['industry'=> "testOne"]);
        $userOne = factory(User::class)->create(['bussiness'=>'testOne']);
        factory(User::class)->create(['bussiness'=>'testTwo']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenRoleIsPresentInTheRequest_filtersResultByThatRole()
    {
        $request = new Request(['role'=> "user"]);
        $userOne = factory(User::class)->create(['role'=>'user']);
        factory(User::class)->create(['role'=>'admin']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenPositionIsPresentInTheRequest_filtersResultByThatPosition()
    {
        $request = new Request(['position'=> "positionOne"]);
        $userOne = factory(User::class)->create(['position'=>'positionOne']);
        factory(User::class)->create(['position'=>'positionTwo']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenAccountManagerIsPresentInTheRequest_filtersResultByThatAccountManager()
    {
        $managerOneId = factory(User::class)->create()->id;
        $request = new Request(['actmanager'=> $managerOneId]);
        $managerTwoId = factory(User::class)->create()->id;
        $userOne = factory(User::class)->create(['account_manager'=>$managerOneId]);
        factory(User::class)->create(['account_manager'=>$managerTwoId]);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenSalesManagerIsPresentInTheRequest_filtersResultByThatSalesManager()
    {
        $managerOneId = factory(User::class)->create()->id;
        $request = new Request(['salesmanager'=> $managerOneId]);
        $managerTwoId = factory(User::class)->create()->id;
        $userOne = factory(User::class)->create(['manager'=>$managerOneId]);
        factory(User::class)->create(['manager'=>$managerTwoId]);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_GivesPhoneNumberFormattedWithCountryCode()
    {
        $request = new Request(['country'=> "US"]);
        factory(User::class)->create(['country'=>'US', 'mobile_code'=>"1", 'mobile'=>"9087654321"]);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals("+1 9087654321", $users->first()->mobile);
    }
}
