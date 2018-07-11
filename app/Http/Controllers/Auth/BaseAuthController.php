<?php

namespace App\Http\Controllers\Auth;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\User\AccountActivate;
use App\User;
use Illuminate\Http\Request;

class BaseAuthController extends Controller
{
    //Required Fields for Zoho
    public function reqFields($user, $email)
    {
        $user = $user->where('email', $email)->first();
        if ($user) {
            $xml = '      <Leads>
                        <row no="1">
                        <FL val="Lead Source">Faveo Billing</FL>
                        <FL val="Company">'.$user->company.'</FL>
                        <FL val="First Name">'.$user->first_name.'</FL>
                        <FL val="Last Name">'.$user->last_name.'</FL>
                        <FL val="Email">'.$user->email.'</FL>
                        <FL val="Manager">'.$user->manager.'</FL>

                        <FL val="Phone">'.$user->mobile_code.''.$user->mobile.'</FL>
                        <FL val="Mobile">'.$user->mobile_code.''.$user->mobile.'</FL>
                        <FL val="Industry">'.$user->bussiness.'</FL>
                        <FL val="City">'.$user->town.'</FL>
                        <FL val="Street">'.$user->address.'</FL>
                        <FL val="State">'.$user->state.'</FL>
                        <FL val="Country">'.$user->country.'</FL>

                        <FL val="Zip Code">'.$user->zip.'</FL>
                         <FL val="No. of Employees">'.$user->company_size.'</FL>
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
        $key = ApiKey::where('id', 1)->value('msg91_auth_key');
        $response = $client->request('GET', 'https://control.msg91.com/api/sendotp.php', [
            'query' => ['authkey' => $key, 'mobile' => $number],
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
    public function sendForReOtp($mobile, $code)
    {
        $client = new \GuzzleHttp\Client();
        $number = $code.$mobile;
        $key = ApiKey::where('id', 1)->value('msg91_auth_key');
        $response = $client->request('GET', 'https://control.msg91.com/api/retryotp.php', [
            'query' => ['authkey' => $key, 'mobile' => $number],
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
        // dd($request->allow());
        $this->validate($request, [
            'email'  => 'required|email',
            'code'   => 'required|numeric',
            'mobile' => 'required|numeric',
        ]);
        $email = $request->oldemail;
        $newEmail = $request->newemail;
        $number = $request->oldnumber;
        $newNumber = $request->newnumber;
        User::where('email', $email)->update(['email'=>$newEmail, 'mobile'=>$newNumber]);

        try {
            $code = $request->input('code');
            $mobile = $request->input('mobile');
            $userid = $request->input('id');
            $email = $request->input('email');
            $pass = $request->input('password');
            $number = $code.$mobile;

            $result = $this->sendOtp($mobile, $code);
            $method = 'POST';

            $this->sendActivation($email, $method, $pass);
            $response = ['type' => 'success', 'message' => 'Activation link has been sent to '.$email.'.<br>OTP has been sent to '.$number.'.<br>Please enter the OTP received on your mobile No below. Incase you did not recieve OTP,please get in touch with us on <a href="mailto:support@faveohelpdesk.com">support@faveohelpdesk.com</a>'];

            return response()->json($response);
        } catch (\Exception $ex) {
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
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
            // var_dump($temp_id);
            //  die();
            $to = $user->email;
            $subject = $template->name;
            $data = $template->data;
            $replace = ['name' => $user->first_name.' '.$user->last_name, 'username' => $user->email, 'password' => $str, 'url' => $url];
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
}
