<?php

namespace Tests;

use App\Http\Controllers\Update\SyncBillingToLatestVersion;
use App\Model\Common\Setting;
use Illuminate\Http\Request;

class SyncBillingToLatestVersionTest extends DBTestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_sync_syncBillingToLatestVersion_updateversion()
    {
        $latestVersion = 'v1.5.3';
        $olderVersion = Setting::factory()->create();
        $response = (new SyncBillingToLatestVersion())->sync(new Request(['latestVersion' => $latestVersion, 'olderVersion' => $olderVersion->version]));
        $this->assertDatabaseHas('settings', ['version'=>'v1.5.3']);
    }
}
