<?php

namespace Tests\Unit;

use App\Http\Controllers\FreeTrailController;
use App\User;
use App\Model\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\DBTestCase;




class FreeTrailControllerTest extends DBTestCase
{

    public function test_firstLoginAtem_generateinvoiceorder_returnstatus200()
    {
        $this->assertFalse(auth()->check());
        $user = factory(User::class)->create(['role' => 'user', 'country' => 'IN']);
        factory(Product::class)->create();
        $auth = Auth::loginUsingId($user->id);
        $this->assertEquals($user->id, $auth->id);
        $this->assertEquals(0,$auth->first_time_login);
        $this->actingAs($user);
        $response = (new FreeTrailController())->firstLoginAtem(new Request(['id' => $user->id]));
        $response=$response->getOriginalContent();
        $this->assertEquals('Invoice and Order has been generated successfully',$response['message']);
        $this->assertEquals(true,$response['success']);



    }



}
