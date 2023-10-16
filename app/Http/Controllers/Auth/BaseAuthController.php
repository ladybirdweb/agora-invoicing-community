<?php

namespace App\Http\Controllers\Auth;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Model\Common\StatusSetting;
use App\Model\User\AccountActivate;
use App\User;
use App\VerificationAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BaseAuthController extends Controller
{
    //Required Fields for Zoho
    public function reqFields($user, $email)
    {
        $user = $user->where('email', $email)->first();
        $country = \DB::table('countries')->where('country_code_char2', $user->country)->pluck('nicename')->first();
        $state = \DB::table('states_subdivisions')->where('state_subdivision_code', $user->state)->pluck('state_subdivision_name')->first();
        $phone = $user->mobile;
        $code = $user->mobile_code;
        if ($user) {
            $xml = '      <Leads>
                        <row no="1">
                        <FL val="Lead Source">Faveo Billing</FL>
                        <FL val="Company">'.$user->company.'</FL>
                        <FL val="First Name">'.$user->first_name.'</FL>
                        <FL val="Last Name">'.$user->last_name.'</FL>
                        <FL val="Email">'.$user->email.'</FL>
                        <FL val="Manager">'.$user->manager.'</FL>
                         <FL val="Phone">'.$code.''.$phone.'</FL>
                        <FL val="Mobile">'.$code.''.$phone.'</FL>
                        <FL val="Industry">'.$user->bussiness.'</FL>
                        <FL val="City">'.$user->town.'</FL>
                        <FL val="Street">'.$user->address.'</FL>
                        <FL val="State">'.$state.'</FL>
                        <FL val="Country">'.$country.'</FL>
                        <FL val="Zip Code">'.$user->zip.'</FL>
                        </row>
                        </Leads>';

            return $xml;
        }
    }

    /**
     * Sends Otp.
     */
    public function sendOtp($mobile, $code)
    {
        $client = new \GuzzleHttp\Client();
        $number = $code.$mobile;
        $key = ApiKey::where('id', 1)->select('msg91_auth_key', 'msg91_sender')->first();

        $response = $client->request('GET', 'https://api.msg91.com/api/v5/otp', [
            'query' => ['authkey' => $key->msg91_auth_key, 'mobiles' => $number, 'sender' => $key->msg91_sender, 'template_id' => '60979666dc49883cda77961b', 'OPT' => '', 'form_params' => ['var' => '']],

            // 'query' => ['authkey' => $key->msg91_auth_key, 'mobile' => $number, 'sender'=>$key->msg91_sender],
        ]);

        $send = $response->getBody()->getContents();
        $array = json_decode($send, true);
        if ($array['type'] == 'error') {
            throw new \Exception($array['message']);
        }

        return $array['type'];
    }

    /**
     * ReSends Otp.
     */
    public function sendForReOtp($mobile, $code, $type)
    {
        $client = new \GuzzleHttp\Client();
        $number = $code.$mobile;
        $key = ApiKey::where('id', 1)->value('msg91_auth_key');

        $response = $client->request('GET', 'https://api.msg91.com/api/v5/otp/retry', [
            'query' => ['authkey' => $key, 'mobile' => $number, 'retrytype' => $type],
        ]);
        $send = $response->getBody()->getContents();
        $array = json_decode($send, true);
        if ($array['type'] == 'error') {
            throw new \Exception($array['message']);
        }

        return $array['type'];
    }

    /**
     * Sends otp and email for confirmatiob.
     */
    public function requestOtpFromAjax(Request $request)
    {
        $this->validate($request, [
            'verify_email'   => 'sometimes|required|verify_email|email',
            'verify_email'   => 'sometimes|required||verify_country_code|numeric',
            'verify_email'   => 'sometimes|required|verify_number|numeric',
        ]);
        $email = $request->oldemail;
        $newEmail = $request->newemail;
        $number = ltrim($request->oldnumber, '0');
        $newNumber = ltrim($request->newnumber, '0');
        User::where('email', $email)->update(['email'=>$newEmail, 'mobile'=>$newNumber]);

        try {
            $code = $request->input('code');
            $mobile = ltrim($request->input('mobile'), '0');
            $userid = $request->input('id');
            $email = $request->input('email');
            $pass = $request->input('password');
            $number = '(+'.$code.') '.$mobile;
            $mobileStatus = StatusSetting::pluck('msg91_status')->first();
            $companyEmail = Setting::find(1)->company_email;
            $msg1 = '';
            $msg2 = '';
            if ($mobileStatus == 1) {
                $result = $this->sendOtp($mobile, $code);
                $msg1 = 'OTP has been sent to '.$number.'.<br>Please enter the 
            OTP received on your mobile No below. Incase you did not recieve OTP,
            please get in touch with us on <a href=mailto:'.$companyEmail.'>
            '.$companyEmail.'</a>';
            }
            $method = 'POST';
            $emailStatus = StatusSetting::pluck('emailverification_status')->first();
            if ($emailStatus == 1) {
                $this->sendActivation($email, $method, $pass);
                $msg2 = 'Activation link has been sent to '.$email;
            }
            $user = User::where('email', $email)->first();
            $emailAttempt = ($user->active == 1) ? 1 : 0;
            $mobileAttempt = ($user->mobile_verified == 1) ? 1 : 0;

            verificationAttempt::create([
                'user_id' => $user->id,
                'email_attempt' => $emailAttempt,
                'mobile_attempt' => $mobileAttempt,
            ]);
            $response = ['type' => 'success',
                'message'           => $msg1.'<br><br>'.$msg2, ];

            return response()->json($response);
        } catch (\Exception $ex) {
            $response = ['type' => 'fail',
                'message'           => $ex->getMessage(), ];
            $result = [$ex->getMessage()];

            return response()->json(compact('response'), 500);
        }
    }

    public function sendActivation($email, $method)
    {
        $user = User::where('email', $email)->first();
        $contact = getContactData();
        if (! $user) {
            throw new \Exception('User with this email does not exist');
        }

        try {
            $activate_model = new AccountActivate();

            if ($method == 'GET') {
                $activate_model = $activate_model->where('email', $email)->first();
                $token = $activate_model->token;
            } else {
                $token = Str::random(40);
                $activate_model->create(['email' => $email, 'token' => $token]);
            }

            $url = url("activate/$token");

            // Check the settings
            $settings = \App\Model\Common\Setting::find(1);

            // Retrieve the template
            $template = \App\Model\Common\Template::find($settings->welcome_mail);
            $website_url = url('/');
            $replace = [
                'name' => $user->first_name.' '.$user->last_name,
                'username' => $user->email,
                'url' => $url,
                'website_url' => $website_url,
                'contact' => $contact['contact'],
                'logo' => $contact['logo'],
                'company_email' => $settings->company_email,
            ];

            $type = '';
            if ($template) {
                $type_id = $template->type;
                $temp_type = new \App\Model\Common\TemplateType();
                $type = $temp_type->where('id', $type_id)->first()->name;
            }

            $mail = new \App\Http\Controllers\Common\PhpMailController();
            $mail->SendEmail($settings->email, $user->email, $template->data, $template->name, $replace, $type);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    protected function addUserToPipedrive($user, $pipeDriveStatus)
    {
        if ($pipeDriveStatus) {
            $token = ApiKey::pluck('pipedrive_api_key')->first();
            $result = $this->searchUserPresenceInPipedrive($user->email, $token);
            if (! $result) {
                $countryFullName = Country::where('country_code_char2', $user->country)->pluck('nicename')->first();
                $pipedrive = new \Devio\Pipedrive\Pipedrive($token);
                $orgId = $pipedrive->organizations->add(['name' => $user->company])->getContent()->data->id;
                $person = $pipedrive->persons()->add(['name' => $user->first_name.' '.$user->last_name, 'email' => $user->email,
                    'phone' => '+'.$user->mobile_code.$user->mobile, 'org_id' => $orgId, ]);

                $person = $pipedrive->persons()->add(['name' => $user->first_name.' '.$user->last_name, 'email' => $user->email,
                    'phone' => '+'.$user->mobile_code.$user->mobile, 'org_id' => $orgId, 'af1c1908b70a61f2baf8b33a975a185cce1aefe5' => $countryFullName, ]);
                $personId = $person->getContent()->data->id;
                $organization = $pipedrive->deals()->add(['title' => $user->company.' '.'deal', 'person_id' => $personId, 'org_id' => $orgId]);
            }
        }
    }

    private function searchUserPresenceInPipedrive($email, $token)
    {
        $pipedriveUrl = 'https://api.pipedrive.com/v1/persons/search?term='.$email.'&api_token='.$token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $pipedriveUrl);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result)->data->items;
    }

    protected function addUserToZoho($user, $zohoStatus)
    {
        if ($zohoStatus) {
            $zoho = $this->reqFields($user, $user->email);
            $auth = ApiKey::where('id', 1)->value('zoho_api_key');
            $zohoUrl = 'https://crm.zoho.com/crm/private/xml/Leads/insertRecords??duplicateCheck=1&';
            $query = 'authtoken='.$auth.'&scope=crmapi&xmlData='.$zoho;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $zohoUrl);
            /* allow redirects */
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            /* return a response into a variable */
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            /* times out after 30s */
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            /* set POST method */
            curl_setopt($ch, CURLOPT_POST, 1);
            /* add POST fields parameters */
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl.

            //Execute cUrl session
            $response = curl_exec($ch);
            curl_close($ch);
        }
    }

    protected function addUserToMailchimp($user, $mailchimpStatus)
    {
        if ($mailchimpStatus) {
            $mailchimp = new \App\Http\Controllers\Common\MailChimpController();
            $mailchimp->addSubscriber($user->email);
        }
    }

    public function emailverificationAttempt($user)
    {
        $attempt = $user->verificationAttempts->first();

        if ($attempt->email_attempt) {
            $attempt->email_attempt = $attempt->email_attempt + 1;
            $attempt->save();
        } else {
            verificationAttempt::where('user_id', $user->id)->update(['email_attempt' => 1]);
        }
    }

    public function mobileVerificationAttempt($user)
    {
        $mobileAttempt = $user->verificationAttempts->first();

        if ($mobileAttempt->mobile_attempt) {
            $mobileAttempt->mobile_attempt = $mobileAttempt->mobile_attempt + 1;
            $mobileAttempt->save();
        } else {
            verificationAttempt::where('user_id', $user->id)->update(['mobile_attempt' => 1]);
        }
    }
}
