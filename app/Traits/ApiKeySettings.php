<?php

namespace App\Traits;

use App\ApiKey;
use App\Model\Common\Mailchimp\MailchimpSetting;
use App\Model\Common\Setting;
use App\Model\Common\StatusSetting;
use DateTime;
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
        ApiKey::find(1)->update(['msg91_auth_key'=>$key, 'msg91_sender'=>$request->input('msg91_sender')]);

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
     * Update Domain Check status In Database
     */
    public function updatedomainCheckDetails(Request $request)
    {
        $status = $request->input('status');
        StatusSetting::find(1)->update(['domain_check'=>$status]);

        return ['message' => 'success', 'update'=>'Domain Check Status Updated'];
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

    public function updatepipedriveDetails(Request $request)
    {
        $pipedriveKey = $request->input('pipedrive_key');
        $status = $request->input('status');
        StatusSetting::find(1)->update(['pipedrive_status'=>$status]);
        ApiKey::find(1)->update(['pipedrive_api_key'=>$pipedriveKey]);
    }

    public function updateMailchimpProductStatus(Request $request)
    {
        StatusSetting::first()->update(['mailchimp_product_status'=>$request->input('status')]);

        return ['message' => 'success', 'update'=>'Mailchimp Products Group Status Updated'];
    }

    public function updateMailchimpIsPaidStatus(Request $request)
    {
        StatusSetting::first()->update(['mailchimp_ispaid_status'=>$request->input('status')]);

        return ['message' => 'success', 'update'=>'Mailchimp is Paid Status Updated'];
    }

    public function updateMailchimpDetails(Request $request)
    {
        $chimp_auth_key = $request->input('mailchimp_auth_key');
        $status = $request->input('status');
        StatusSetting::find(1)->update(['mailchimp_status'=>$status]);
        MailchimpSetting::find(1)->update(['api_key'=>$chimp_auth_key]);

        return ['message' => 'success', 'update'=>'Mailchimp Settings Updated'];
    }

    public function updateTermsDetails(Request $request)
    {
        $terms_url = $request->input('terms_url');
        $status = $request->input('status');
        StatusSetting::find(1)->update(['terms'=>$status]);
        ApiKey::find(1)->update(['terms_url'=>$terms_url]);

        return ['message' => 'success', 'update'=>'Terms Url Updated'];
    }

    /**
     * Get Date.
     */
    public function getDate($dbdate)
    {
        $created = new DateTime($dbdate);
        $tz = \Auth::user()->timezone()->first()->name;
        $created->setTimezone(new \DateTimeZone($tz));
        $date = $created->format('M j, Y, g:i a '); //5th October, 2018, 11:17PM
        $newDate = $date;

        return $newDate;
    }

    public function getDateFormat($dbdate = '')
    {
        $created = new DateTime($dbdate);
        $tz = \Auth::user()->timezone()->first()->name;
        $created->setTimezone(new \DateTimeZone($tz));
        $date = $created->format('Y-m-d H:m:i');

        return $date;
    }

    public function saveConditions()
    {
        if (\Input::get('expiry-commands') && \Input::get('activity-commands')) {
            $expiry_commands = \Input::get('expiry-commands');
            $expiry_dailyAt = \Input::get('expiry-dailyAt');
            $activity_commands = \Input::get('activity-commands');
            $activity_dailyAt = \Input::get('activity-dailyAt');
            $activity_command = $this->getCommand($activity_commands, $activity_dailyAt);
            $expiry_command = $this->getCommand($expiry_commands, $expiry_dailyAt);
            $jobs = ['expiryMail' => $expiry_command, 'deleteLogs' =>  $activity_command];
            $this->storeCommand($jobs);
        }
    }

    public function getCommand($command, $daily_at)
    {
        if ($command == 'dailyAt') {
            $command = "dailyAt,$daily_at";
        }

        return $command;
    }

    public function storeCommand($array = [])
    {
        $command = new \App\Model\Mailjob\Condition();
        $commands = $command->get();
        if ($commands->count() > 0) {
            foreach ($commands as $condition) {
                $condition->delete();
            }
        }
        if (count($array) > 0) {
            foreach ($array as $key => $save) {
                $command->create([
                    'job'   => $key,
                    'value' => $save,
                ]);
            }
        }
    }

    public function showFileStorage()
    {
        $fileStorage = Setting::first()->value('file_storage');

        return view('themes.default1.common.setting.file-storage', compact('fileStorage'));
    }

    public function updateStoragePath(Request $request)
    {
        $updatedPath = Setting::find(1)->update(['file_storage'=>$request->input('fileuploadpath')]);

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }
}
