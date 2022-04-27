<?php

namespace App\Listeners;

use App\Events\LicenseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\ThirdPartyApp;

class VerifyLicenseCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->api_key_secret = $this->license->license_api_secret;
        $this->url = $this->license->license_api_url;

        $this->token = ThirdPartyApp::where('app_secret', 'LicenseSecret')->value('app_key');
    }

    /**
     * Handle the event.
     *
     * @param  LicenseCreated  $event
     * @return void
     */
    public function handle(LicenseCreated $event)
    {
        dd($event);
        
        $url = $this->url;
        $api_key_secret = $this->api_key_secret;
        $token = $this->token;
        $getkey = $this->getCurl($url.'api/admin/viewApiKeys?token='.$token);
        $details = json_encode($getkey,true);
        dd($details);

        if($event->licenseApiSecret !==  $details["data"][0]["api_key_secret"])
        {
         return ['message' => 'failed', 'update'=>'Invalid licenseDetails',$event->'licensekey'];
        }
    }
}
