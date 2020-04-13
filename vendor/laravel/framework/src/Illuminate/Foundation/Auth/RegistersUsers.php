<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Model\Payment\Currency;
use App\Model\Common\StatusSetting;
use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Model\User\AccountActivate;
use Facades\Spatie\Referer\Referer;
use App\User;
use Bugsnag;
// use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Validator;

trait RegistersUsers
{
    use RedirectsUsers;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        // return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister(ProfileRequest $request, User $user, AccountActivate $activate)
    {
        try {
            $pass = $request->input('password');
            $fname = strip_tags($request->input('first_name'));
            $lname = strip_tags($request->input('last_name'));
            $address = strip_tags($request->input('address'));
            $company =  strip_tags($request->input('company'));
            $zip = strip_tags($request->input('zip'));
            $user_name = strip_tags($request->input('email'));
            $country = $request->input('country');
            $currency = Setting::find(1)->default_currency;
            $currency_symbol = Setting::find(1)->default_symbol;
            $cont = new \App\Http\Controllers\Front\PageController();
            $location = $cont->getLocation();
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($location['iso_code']);
            $states = \App\Model\Common\State::pluck('state_subdivision_name', 'state_subdivision_code')->toArray();
            $state_code = $location['iso_code'] . "-" . $location['state'];
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($state_code);
            $userCountry = Country::where('country_code_char2', $country)->first();
            $currencyStatus = $userCountry->currency->status;
            if ($currencyStatus == 1) {
                $currency = $userCountry->currency->code;
                $currency_symbol = $userCountry->currency->symbol;
            }
          
            $password = \Hash::make($pass);
            $user->password = $password;
            $user->town=$location['city'];
            $user->profile_pic="";
            $user->active=0;
            $user->debit=0;
            $user->mobile_verified=0;
            $user->currency_symbol = $currency_symbol;
            $user->mobile = ltrim($request->input('mobile'), '0');
            $user->role = 'user';
            $user->address = $address;
            $user->first_name = $fname;
            $user->last_name = $lname;
            $user->company = $company;
            $user->zip = $zip;
            $user->user_name = $user_name;

            $user->manager = $user->assignSalesManager();
            $user->ip = $location['ip'];
            $user->currency = $currency;
            $referer = Referer::get(); // 'google.com'
            // $user->referrer = $referer;
            $user->timezone_id = \App\Http\Controllers\Front\CartController::getTimezoneByName($location['timezone']);
            $emailMobileSetting = StatusSetting::select('emailverification_status', 'msg91_status')->first();
            if ($emailMobileSetting->emailverification_status == 0 && $emailMobileSetting->msg91_status ==1) {
                $user->mobile_verified=0;
                $user->active=1;
                $user->fill($request->except('password', 'address', 'first_name', 'last_name', 'company', 'zip', 'user_name','mobile'))->save();
                $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your Submission has been received successfully. Verify your Mobile to log into the Website.'];
            } elseif ($emailMobileSetting->emailverification_status ==1 && $emailMobileSetting->msg91_status ==0) {
                $user->mobile_verified=1;
                $user->active=0;
                $user->fill($request->except('password', 'address', 'first_name', 'last_name', 'company', 'zip', 'user_name','mobile'))->save();
                $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your Submission has been received successfully. Verify your Email to log into the Website.'];
            } elseif ($emailMobileSetting->emailverification_status ==0 && $emailMobileSetting->msg91_status ==0) {
                $user->mobile_verified=1;
                $user->active=1;
                $user->fill($request->except('password', 'address', 'first_name', 'last_name', 'company', 'zip', 'user_name','mobile'))->save();
                $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your have been Registered Successfully.'];
            } else {
                if ($user) {
                    $user->fill($request->except('password', 'address', 'first_name', 'last_name', 'company', 'zip', 'user_name','mobile'))->save();
                    $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your Submission has been received successfully. Verify your Email and Mobile to log into the Website.'];
                }
            }
           
            activity()->log('User <strong>' . $request->input('first_name'). ' '.$request->input('last_name').  '</strong> was created');
            // $this->accountManagerMail($user);
             
           
            return response()->json($response);
        } catch (\Exception $ex) {
            dd($ex);
            Bugsnag::notifyException($ex);
            app('log')->error($ex->getMessage());
            $result = [$ex->getMessage()];
            return response()->json($result);
        }
    }
    public function sendActivationByGet($email, Request $request)
    {
        try {
            $mail = $this->sendActivation($email, $request->method());
            if ($mail == 'success') {
                return redirect()->back()->with('success', 'Activation link has sent to your email address');
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
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
            $subject = $template->name;
            $data = $template->data;
            $replace = ['name' => $user->first_name.' '.$user->last_name, 'username' => $user->email, 'password' => $str, 'url' => $url];
            $type = '';
            if ($template) {
                $type_id = $template->type;
                $temp_type = new \App\Model\Common\TemplateType();
                $type = $temp_type->where('id', $type_id)->first()->name;
            }
            //dd($type);
            $templateController = new \App\Http\Controllers\Common\TemplateController();
            $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);

            return $mail;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function Activate($token, AccountActivate $activate, Request $request, User $user)
    {
        try {
            if ($activate->where('token', $token)->first()) {
                $email = $activate->where('token', $token)->first()->email;
            } else {
                throw new NotFoundHttpException();
            }
            
            $url = 'auth/login';
            $user = $user->where('email', $email)->first();
            $mobileStatus = StatusSetting::pluck('msg91_status')->first();
            if ($user->where('email', $email)->first()) {
                if ($mobileStatus == 0) {//If Mobile Verification is not on from Admin Panel
                    $user->mobile_verified = 1;
                }
                $user->active = 1;
                $user->save();
                $mailchimp = new \App\Http\Controllers\Common\MailChimpController();
                // $r = $mailchimp->addSubscriber($user->email);
                if (\Session::has('session-url')) {
                    $url = \Session::get('session-url');

                    return redirect($url);
                }

                return redirect($url)->with('success', 'Email verification successful.. Please login to access your account');
            } else {
                throw new NotFoundHttpException();
            }
        } catch (\Exception $ex) {
            if ($ex->getCode() == 400) {
                return redirect($url)->with('success', 'Email verification successful, Please login to access your account');

                return redirect($url);
            }

            return redirect($url)->with('fails', $ex->getMessage());
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
                    'name'     => 'required|max:255',
                    'email'    => 'required|email|max:255|unique:users',
                    'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    public function create(array $data)
    {
        return User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => bcrypt($data['password']),
        ]);
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
            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/my-invoices';
        }
    }



    public function sendForReOtp($mobile, $code)
    {
        $client = new \GuzzleHttp\Client();
        $number = $code.$mobile;
        $response = $client->request('GET', 'https://control.msg91.com/api/retryotp.php', [
            'query' => ['authkey' => '54870AO9t5ZB1IEY5913f8e2', 'mobile' => $number],
        ]);
        $send = $response->getBody()->getContents();
        $array = json_decode($send, true);
        if ($array['type'] == 'error') {
            throw new \Exception($array['message']);
        }

        return $array['type'];
    }

    public function requestOtp(Request $request)
    {
        $this->validate($request, [
            'code'   => 'required|numeric',
            'mobile' => 'required|numeric',
        ]);

        try {
            $code = $request->input('code');
            $mobile = $request->input('mobile');
            $number = $code.$mobile;
            $result = $this->sendOtp($mobile, $code);
            $response = ['type' => 'success', 'message' => 'OTP has been sent to '.$number];

            return response()->json($response);
        } catch (\Exception $ex) {
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
        }
    }



    public function postOtp(Request $request)
    {
        $this->validate($request, [
            'otp' => 'required|numeric',
        ]);

        try {
            $code = $request->input('code');
            $mobile = $request->input('mobile');
            $otp = $request->input('otp');
            $userid = $request->input('id');
            $verify = $this->verifyOtp($mobile, $code, $otp);
            $array = json_decode($verify, true);
            if ($array['type'] == 'error') {
                throw new \Exception($array['message']);
            }

            $user = User::find($userid);
            if ($user) {
                $user->mobile = $mobile;
                $user->mobile_code = $code;
                $user->mobile_verified = 1;
                $user->save();
            }
            $check = $this->checkVerify($user);
            $response = ['type' => 'success', 'proceed' => $check, 'user_id' => $userid, 'message' => 'Mobile verified..'];

            return response()->json($response);
            // return redirect('/login');
        } catch (\Exception $ex) {
            $result = [$ex->getMessage()];
            if ($ex->getMessage() == 'otp_not_verified') {
                $result = ['OTP Not Verified!'];
            }

            return response()->json(compact('result'), 500);
        }
    }

    public function verifyEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        try {
            $email = $request->input('email');
            $userid = $request->input('id');
            $user = User::find($userid);
            $check = $this->checkVerify($user);
            $method = 'POST';
            //$this->sendActivation($email, $request->method());
            $this->sendActivation($email, $method);
            $response = ['type' => 'success', 'proceed' => $check, 'email' => $email, 'message' => 'Activation link has been sent to '.$email];

            return response()->json($response);
        } catch (\Exception $ex) {
            //dd($ex);
            $result = [$ex->getMessage()];

            return response()->json(compact('result'), 500);
        }
    }

    public function checkVerify($user)
    {
        $check = false;
        if ($user->active == '1' && $user->mobile_verified == '1') {
            \Auth::login($user);
            $check = true;
        }

        return $check;
    }

    public function accountManager()
    {
        
        // $users = new User();
        // $account_count = $users->select(\DB::raw("count('manager') as count"), 'manager')
        //         ->whereNotNull('manager')
        //         ->groupBy('manager')
        //         ->pluck('count', 'manager')
        //         ->toArray();
              
        // if ($account_count) {
        //     $manager = array_keys($account_count, min($account_count))[0];
        // }


        
        $managers = User::where('role', 'admin')->where('position', 'manager')->pluck('id', 'first_name')->toArray();
        if (count($managers)>0) {
            $randomized[] = array_rand($managers);
            shuffle($randomized);
            $manager = $managers[$randomized[0]];
        } else {
            $manager = '';
        }
        
        return $manager;
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }
}
