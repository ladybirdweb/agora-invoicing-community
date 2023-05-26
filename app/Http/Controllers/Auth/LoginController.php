<?php

namespace App\Http\Controllers\Auth;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Common\Bussiness;
use App\Model\Common\ChatScript;
use App\Model\Common\StatusSetting;
use App\SocialLogin;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        try {
            $bussinesses = Bussiness::pluck('name', 'short')->toArray();
            $status = StatusSetting::select('recaptcha_status', 'msg91_status', 'emailverification_status', 'terms')->first();
            $apiKeys = ApiKey::select('nocaptcha_sitekey', 'captcha_secretCheck', 'msg91_auth_key', 'terms_url')->first();
            $analyticsTag = ChatScript::where('google_analytics', 1)->where('on_registration', 1)->value('google_analytics_tag');
            $location = getLocation();

            return view('themes.default1.front.auth.login-register', compact('bussinesses', 'location', 'status', 'apiKeys', 'analyticsTag'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            $error = $ex->getMessage();
        }
    }

    public function login(Request $request)
    {
        $apiKeys = StatusSetting::value('recaptcha_status');
        $captchaRule = $apiKeys ? 'required|' : 'sometimes|';
        $this->validate($request, [
            'email1' => 'required',
            'password1' => 'required',
            'g-recaptcha-response' => $captchaRule.'captcha',
        ], [
            'g-recaptcha-response.required' => 'Robot Verification Failed. Please Try Again.',
            'email1.required' => 'Please Enter an Email',
            'password1.required' => 'Please Enter Password',
        ]);
        $usernameinput = $request->input('email1');
        $password = $request->input('password1');
        $credentialsForEmail = ['email' => $usernameinput, 'password' => $password, 'active' => '1', 'mobile_verified' => '1'];
        $auth = \Auth::attempt($credentialsForEmail, $request->has('remember'));
        if (! $auth) {//Check for correct email
            $credentialsForusername = ['user_name' => $usernameinput, 'password' => $password, 'active' => '1', 'mobile_verified' => '1'];
            $auth = \Auth::attempt($credentialsForusername, $request->has('remember'));
        }
        if (! $auth) {//Check for correct username
            $user = User::where('email', $usernameinput)->orWhere('user_name', $usernameinput)->first();
            if (! $user) {
                return redirect()->back()
                            ->withInput()
                            ->withErrors([
                                'email1' => 'Please Enter a valid Email',
                            ]);
            }

            if (! \Hash::check($password, $user->password)) {//Check for correct password
                return redirect()->back()
                ->withInput()
                ->withErrors([
                    'password1' => 'Please Enter a valid Password',
                ]);
            }
            if ($user && ($user->active !== 1 || $user->mobile_verified !== 1)) {
                return redirect('verify')->with('user', $user);
            }
        }
        if (\Auth::user()->is_2fa_enabled == 1) {
            $userId = \Auth::user()->id;
            \Auth::logout();
            $request->session()->put('2fa:user:id', $userId);

            return redirect('2fa/validate');
        }
        activity()->log('Logged In');

        return redirect($this->redirectPath());
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        // dd('vbn');

        if (\Session::has('session-url')) {
            $url = \Session::get('session-url');
            // dd($url);
            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/'.$url;
        } else {
            $user = \Auth::user()->role;
            // dd($user);
            $redirectResponse = redirect()->intended('/');
            $intendedUrl = $redirectResponse->getTargetUrl();
            if (strpos($intendedUrl, 'autopaynow') == false) {
                // return ($user == 'user') ? 'mobile/verification' : '/';
                return ($user == 'user') ? 'verify' : '/';
            }

            return property_exists($this, 'redirectTo') ? $intendedUrl : '/';
        }
    }

    //  public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     try {
    //          $user = Socialite::driver('github')->stateless()->user();
    // // dd($user);

    // $localUser = App\User::updateOrCreate([
    //         'email' => $user->getEmail(),
    //         'password' => Hash::make(Str::random()),
    //         'username' => $user->getNickName(),
    //     ]);
    //     dd($localUser);

    // \Auth::login($localUser);

    // return redirect('/');

            // }

    // }

    //

    public function redirectToGithub($provider)
    {
        $details = SocialLogin::where('type', $provider)->first();
        \Config::set("services.$provider.redirect", $details->redirect_url);
        \Config::set("services.$provider.client_id", $details->client_id);
        \Config::set("services.$provider.client_secret", $details->client_secret);

        return Socialite::driver($provider)->redirect();
    }

    public function handler($provider)
    {
        $details = SocialLogin::where('type', $provider)->first();
        \Config::set("services.$provider.redirect", $details->redirect_url);
        \Config::set("services.$provider.client_id", $details->client_id);
        \Config::set("services.$provider.client_secret", $details->client_secret);
        $githubUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            'email' => $githubUser->getemail(),
        ],
            [
                'user_name' => $githubUser->getNickName(),
                'first_name' => $githubUser->getname(),
                'role' => 'user',
                'password' => Hash::make(Str::random()),
                //   'mobile_verified' => '1',
                'active' => '1',
            ]);

        \Auth::login($user);
        //   return redirect('/');
        if ($user && ($user->mobile_verified !== 1)) {
            return redirect('verify')->with('user', $user);
        }

        return redirect($this->redirectPath());
    }

         public function mobileVerification()
         {
             dd('hi');
         }

//
//  public function view() {
//         $socialLogins = SocialLogin::get();
//         return view("themes.default1.common.socialLogins",compact('socialLogins'));
//     }

//     public function edit($id) {
//         $socialLogins = SocialLogin::where('id',$id)->first();
//         return view("themes.default1.common.editSocialLogins",compact('socialLogins'));
//     }

//     public function update(Request $request) {
//         $socialLogins= SocialLogin::where('type',$request->type)->first();
//         $socialLogins->type=$request->type;
//         $socialLogins->client_id=$request->client_id;
//         $socialLogins->client_secret=$request->client_secret;
//         $socialLogins->redirect_url=$request->redirect_url;
//         $socialLogins->save();
//         return redirect()->back();
//     }
}
