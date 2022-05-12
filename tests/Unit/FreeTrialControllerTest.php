<?php

namespace Tests\Unit;

use App\Http\Controllers\FreeTrailController;
use App\Model\Product\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\DBTestCase;

class FreeTrailControllerTest extends DBTestCase
{
    public function test_firstLoginAtem_generateinvoiceorder_returnstatus200()
    {
        $user = factory(User::class)->create(['role' => 'user', 'country' => 'IN']);
        factory(Product::class)->create();
        $auth = Auth::loginUsingId($user->id);
        $this->actingAs($user);
        $response = (new FreeTrailController())->firstLoginAtem(new Request(['id' => $user->id]));
        $response = $response->getOriginalContent();
        $this->assertFalse(auth()->check());
        $this->assertEquals($user->id, $auth->id);
        $this->assertEquals(0, $auth->first_time_login);
        $this->assertEquals('Invoice and Order has been generated successfully', $response['message']);
        $this->assertEquals(true, $response['success']);
    }
}
