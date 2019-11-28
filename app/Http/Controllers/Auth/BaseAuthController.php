<?php

namespace App\Http\Controllers\Auth;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Model\Common\StatusSetting;
use App\Model\User\AccountActivate;
use App\User;
use Illuminate\Http\Request;

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
    public static function sendOtp($mobile, $code)
    {
        $client = new \GuzzleHttp\Client();
        $number = $code.$mobile;
        $key = ApiKey::where('id', 1)->select('msg91_auth_key', 'msg91_sender')->first();

        $response = $client->request('GET', 'https://api.msg91.com/api/v5/otp', [
            'query' => ['authkey' => $key->msg91_auth_key, 'mobile' => $number, 'sender'=>$key->msg91_sender],
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
            'query' => ['authkey' => $key, 'mobile' => $number, 'retrytype'=>$type],
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

    public function sendActivation($email, $method, $str = '')
    {
        try {
            $user = new User();

            $activate_model = new AccountActivate();
            $user = $user->where('email', $email)->first();
            if (!$user) {
                return redirect()->back()->with('fails', 'Invalid Email');
            }

            if ($method == 'GET') {
                $activate_model = $activate_model->where('email', $email)->first();
                $token = $activate_model->token;
            } else {
                $token = str_random(40);
                $activate = $activate_model->create(['email' => $email, 'token' => $token]);
                $token = $activate->token;
            }

            $url = url("activate/$token");
            //check in the settings
            $settings = new \App\Model\Common\Setting();
            $settings = $settings->where('id', 1)->first();

            //template
            $template = new \App\Model\Common\Template();
            $temp_id = $settings->where('id', 1)->first()->welcome_mail;
            $template = $template->where('id', $temp_id)->first();
            $from = $settings->email;
            $to = $user->email;
            $website_url = url('/');
            $subject = $template->name;
            $data = $template->data;
            $replace = ['name' => $user->first_name.' '.$user->last_name,
            'username'         => $user->email, 'password' => $str, 'url' => $url, 'website_url'=>$website_url, ];
            $type = '';

            if ($template) {
                $type_id = $template->type;
                $temp_type = new \App\Model\Common\TemplateType();
                $type = $temp_type->where('id', $type_id)->first()->name;
            }

            //dd($from, $to, $data, $subject, $replace, $type);
            $templateController = new \App\Http\Controllers\Common\TemplateController();
            $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);

            return $mail;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (\Session::has('session-url')) {
            $url = \Session::get('session-url');

            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/'.$url;
        } else {
            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
        }
    }

    protected function addToPipedrive($user)
    {
        $token = ApiKey::pluck('pipedrive_api_key')->first();
        $countryFullName = Country::where('country_code_char2', $user->country)->pluck('nicename')->first();
        $pipedrive = new \Devio\Pipedrive\Pipedrive($token);

        $orgId = $pipedrive->organizations->add(['name'=>$user->company])->getContent()->data->id;
        $person = $pipedrive->persons()->add(['name' => $user->first_name.' '.$user->last_name, 'email'=>$user->email,
            'phone'                                  => '+'.$user->mobile_code.$user->mobile, 'org_id'=>$orgId, ]);

        // $person = $pipedrive->persons()->add(['name' => $user->first_name .' '. $user->last_name,'email'=>$user->email,
        //     'phone'=>'+'.$user->mobile_code.$user->mobile,'org_id'=>$orgId,'af1c1908b70a61f2baf8b33a975a185cce1aefe5'=>$countryFullName]);
        $personId = $person->getContent()->data->id;
        $organization = $pipedrive->deals()->add(['title'=>$user->company.' '.'deal', 'person_id'=>$personId, 'org_id'=>$orgId]);
    }
}
