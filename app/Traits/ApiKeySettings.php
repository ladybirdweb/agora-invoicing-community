<?php

namespace App\Traits;

use App\ApiKey;
use App\Model\Common\StatusSetting;
use Illuminate\Http\Request;

//////////////////////////////////////////////////////////////
//TRAIT FOR SAVING API STATUS AND API KEYS //
////////////////////////////////////////////////////////////////

trait ApiKeySettings
{
    public function licenseDetails(Request $request)
    {
        $status = $request->input('status');
        $licenseApiSecret = $request->input('license_api_secret');
        $licenseApiUrl = $request->input('license_api_url');
        StatusSetting::where('id', 1)->update(['license_status'=>$status]);
        ApiKey::where('id', 1)->update(['license_api_secret'=>$licenseApiSecret, 'license_api_url'=>$licenseApiUrl]);

        return ['message' => 'success', 'update'=>'Licensing Settings Updated'];
    }

    //Save Auto Update status in Database
    public function updateDetails(Request $request)
    {
        $status = $request->input('status');
        $updateApiSecret = $request->input('update_api_secret');
        $updateApiUrl = $request->input('update_api_url');
        StatusSetting::where('id', 1)->update(['update_settings'=>$status]);
        ApiKey::where('id', 1)->update(['update_api_secret'=>$updateApiSecret, 'update_api_url'=>$updateApiUrl]);

        return ['message' => 'success', 'update'=>'Auto Update Settings Updated'];
    }

    /*
     * Update Msg91 Details In Database
     */
    public function updatemobileDetails(Request $request)
    {
        $status = $request->input('status');
        $key = $request->input('msg91_auth_key');
        StatusSetting::find(1)->update(['msg91_status'=>$status]);
        ApiKey::find(1)->update(['msg91_auth_key'=>$key]);

        return ['message' => 'success', 'update'=>'Msg91 Settings Updated'];
    }

    /*
     * Update Zoho Details In Database
     */
    public function updatezohoDetails(Request $request)
    {
        $status = $request->input('status');
        $key = $request->input('zoho_key');
        StatusSetting::find(1)->update(['zoho_status'=>$status]);
        ApiKey::find(1)->update(['zoho_api_key'=>$key]);

        return ['message' => 'success', 'update'=>'Zoho Settings Updated'];
    }

    /*
     * Update Email Status In Database
     */
    public function updateEmailDetails(Request $request)
    {
        $status = $request->input('status');
        StatusSetting::find(1)->update(['emailverification_status'=>$status]);

        return ['message' => 'success', 'update'=>'Email Verification Status Updated'];
    }

    /*
    * Update Twitter Details In Database
    */
    public function updatetwitterDetails(Request $request)
    {
        $consumer_key = $request->input('consumer_key');
        $consumer_secret = $request->input('consumer_secret');
        $access_token = $request->input('access_token');
        $token_secret = $request->input('token_secret');
        $status = $request->input('status');
        StatusSetting::find(1)->update(['twitter_status'=>$status]);
        ApiKey::find(1)->update(['twitter_consumer_key'=>$consumer_key, 'twitter_consumer_secret'=>$consumer_secret, 'twitter_access_token'=>$access_token, 'access_tooken_secret'=>$token_secret]);

        return ['message' => 'success', 'update'=>'Twitter Settings Updated'];
    }

    /*
    * Update Razorpay Details In Database
    */
    public function updateRazorpayDetails(Request $request)
    {
        $rzp_key = $request->input('rzp_key');
        $rzp_secret = $request->input('rzp_secret');
        $apilayer_key = $request->input('apilayer_key');
        $status = $request->input('status');
        StatusSetting::find(1)->update(['rzp_status'=>$status]);
        ApiKey::find(1)->update(['rzp_key'=>$rzp_key, 'rzp_secret'=>$rzp_secret, 'apilayer_key'=>$apilayer_key]);

        return ['message' => 'success', 'update'=>'Razorpay Settings Updated'];
    }
}
