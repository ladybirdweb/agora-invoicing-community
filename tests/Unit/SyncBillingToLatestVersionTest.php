<?php

namespace Tests;

use App\Http\Controllers\SyncBillingToLatestVersion;
use App\Model\Common\Setting;
use Illuminate\Http\Request;
use Tests\TestCase;

class SyncBillingToLatestVersionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_sync_syncBillingToLatestVersion_updateversion()
    {
        $latestVersion = 'v2.0.0';
        $olderVersion = Setting::factory()->create();
        $response = (new SyncBillingToLatestVersion())->sync(new Request(['latestVersion' => $latestVersion, 'olderVersion' => $olderVersion->version]));
        $this->assertDatabaseHas('settings', ['version' => 'v2.0.0']);
    }
}
