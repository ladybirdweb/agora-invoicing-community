<?php

namespace Tests\Unit\Admin\User;

use App\Http\Controllers\User\ClientController;
use App\User;
use Illuminate\Http\Request;
use Tests\DBTestCase;

class ClientControllerTest extends DBTestCase
{
    private $classObject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classObject = new ClientController();
    }

    public function test_getBaseQueryForUserSearch_whenCountryIsPresentInTheRequest_filtersResultByThatCountry()
    {
        $request = new Request(['country' => 'US']);
        User::factory()->create(['country' => 'US']);
        User::factory()->create(['country' => 'IN']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals('UNITED STATES', $users->first()->country);
    }

    public function test_getBaseQueryForUserSearch_whenIndustryIsPresentInTheRequest_filtersResultByThatIndustry()
    {
        $request = new Request(['industry' => 'testOne']);
        $userOne = User::factory()->create(['bussiness' => 'testOne']);
        User::factory()->create(['bussiness' => 'testTwo']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenRoleIsPresentInTheRequest_filtersResultByThatRole()
    {
        $request = new Request(['role' => 'user']);
        $userOne = User::factory()->create(['role' => 'user']);
        User::factory()->create(['role' => 'admin']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenPositionIsPresentInTheRequest_filtersResultByThatPosition()
    {
        $request = new Request(['position' => 'positionOne']);
        $userOne = User::factory()->create(['position' => 'positionOne']);
        User::factory()->create(['position' => 'positionTwo']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenAccountManagerIsPresentInTheRequest_filtersResultByThatAccountManager()
    {
        $managerOneId = User::factory()->create()->id;
        $request = new Request(['actmanager' => $managerOneId]);
        $managerTwoId = User::factory()->create()->id;
        $userOne = User::factory()->create(['account_manager' => $managerOneId]);
        User::factory()->create(['account_manager' => $managerTwoId]);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_whenSalesManagerIsPresentInTheRequest_filtersResultByThatSalesManager()
    {
        $managerOneId = User::factory()->create()->id;
        $request = new Request(['salesmanager' => $managerOneId]);
        $managerTwoId = User::factory()->create()->id;
        $userOne = User::factory()->create(['manager' => $managerOneId]);
        User::factory()->create(['manager' => $managerTwoId]);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals(1, $users->count());
        $this->assertEquals($userOne->id, $users->first()->id);
    }

    public function test_getBaseQueryForUserSearch_GivesPhoneNumberFormattedWithCountryCode()
    {
        $request = new Request(['country' => 'US']);
        User::factory()->create(['country' => 'US', 'mobile_code' => '1', 'mobile' => '9087654321']);
        $methodResponse = $this->getPrivateMethod($this->classObject, 'getBaseQueryForUserSearch', [$request]);
        $users = $methodResponse->get();
        $this->assertEquals('+1 9087654321', $users->first()->mobile);
    }
}
