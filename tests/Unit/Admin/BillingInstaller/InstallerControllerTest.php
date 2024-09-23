<?php

namespace Tests\Unit\Admin\BillingInstaller;

use App\Http\Controllers\BillingInstaller\InstallerController;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class InstallerControllerTest extends TestCase
{
    public function testConfigurationCheck()
    {
//        This will only test the wrong connection because we dont know the database credential
        $request = Request::create('/configurationcheck', 'POST', [
            'host' => 'localhost',
            'databasename' => 'test_db',
            'username' => 'test_user',
            'password' => 'password',
            'port' => '3306',
        ]);

        $controller = new InstallerController();
        $response = $controller->configurationcheck($request);
        $response = TestResponse::fromBaseResponse($response); // Wrap the base response
        $response->assertStatus(200);
        $this->assertEquals(false, json_decode($response->getContent())->mysqli_ok);
    }
}
