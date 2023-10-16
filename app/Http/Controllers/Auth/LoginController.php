<?php

namespace App\Http\Controllers\Auth;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Common\Bussiness;
use App\Model\Common\ChatScript;
use App\Model\Common\Country;
use App\Model\Common\StatusSetting;
use App\SocialLogin;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Session;

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
        $this->middleware('guest')->except(['logout', 'store-basic-details']);
    }

    public function showLoginForm()
    {
        try {
            $bussinesses = Bussiness::pluck('name', 'short')->toArray();
            $status = StatusSetting::select('recaptcha_status', 'msg91_status', 'emailverification_status', 'terms')->first();
            $apiKeys = ApiKey::select('nocaptcha_sitekey', 'captcha_secretCheck', 'msg91_auth_key', 'terms_url')->first();
            $analyticsTag = ChatScript::where('google_analytics', 1)->where('on_registration', 1)->value('google_analytics_tag');
            $location = getLocation();

            $google_status = SocialLogin::select('status')->where('type', 'google')->value('status');
            $github_status = SocialLogin::select('status')->where('type', 'github')->value('status');
            $twitter_status = SocialLogin::select('status')->where('type', 'twitter')->value('status');
            $linkedin_status = SocialLogin::select('status')->where('type', 'linkedin')->value('status');

            return view('themes.default1.front.auth.login-register', compact('bussinesses', 'location', 'status', 'apiKeys', 'analyticsTag', 'google_status', 'github_status', 'linkedin_status', 'twitter_status'));
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
        if (! $auth) { //Check for correct email
            $credentialsForusername = ['user_name' => $usernameinput, 'password' => $password, 'active' => '1', 'mobile_verified' => '1'];
            $auth = \Auth::attempt($credentialsForusername, $request->has('remember'));
        }

        if (! $auth) { //Check for correct username
            $user = User::where('email', $usernameinput)->orWhere('user_name', $usernameinput)->first();
            if (! $user) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'email1' => 'Please Enter a valid Email',
                    ]);
            }

            if (! \Hash::check($password, $user->password)) { //Check for correct password
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

        $this->convertCart();

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
        if (\Session::has('session-url')) {
            $url = \Session::get('session-url');

            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/'.$url;
        } else {
            $user = \Auth::user()->role;
            $redirectResponse = redirect()->intended('/');
            $intendedUrl = $redirectResponse->getTargetUrl();
            if (strpos($intendedUrl, 'autopaynow') == false) {
                return ($user == 'user') ? 'my-invoices' : '/';
            }

            return property_exists($this, 'redirectTo') ? $intendedUrl : '/';
        }
    }

    public function redirectToGithub($provider)//redirect to twitter ,github,google and linkedin
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
        $location = getLocation();

        $state_code = $location['iso_code'].'-'.$location['state'];

        $state = getStateByCode($state_code);

        $existingUser = User::where('email', $githubUser->getEmail())->first();

        if ($existingUser) {
            $existingUser->active = '1';

            if ($existingUser->role == 'admin') {
                $existingUser->role = 'admin';
            } else {
                $existingUser->role = 'user';
            }

            $existingUser->save();
            $user = $existingUser;
        } else {
            $user = User::create([
                'email' => $githubUser->getEmail(),
                'user_name' => $githubUser->getEmail(),
                'first_name' => $githubUser->getName(),
                'active' => '1',
                'role' => 'user',
                'ip' => $location['ip'],
                'timezone_id' => getTimezoneByName($location['timezone']),
                'state' => $state['id'],
                'town' => $location['city'],
                'country' => Country::where('country_name', strtoupper($location['country']))->value('country_code_char2'),
            ]);
        }
        if ($user && ($user->active == 1 && $user->mobile_verified !== 1)) {//check for mobile verification
            return redirect('verify')->with('user', $user);
        }

        Auth::login($user);

        if (\Auth::user()->is_2fa_enabled == 1) {//check for 2fa
            $userId = \Auth::user()->id;
            Session::put('2fa:user:id', $userId);
            \Auth::logout();

            return redirect('2fa/validate');
        }
        if (Auth::check()) {
            $this->convertCart();

            return redirect($this->redirectPath());
        }
    }

    //stores basic details for social logins
    public function storeBasicDetailsss(Request $request)
    {
        try {
            $this->validate($request, [
                'company' => 'required|string',
                'address' => 'required|string',
            ]);

            $user = Auth::user();
            $user->company = $request->company;
            $user->address = $request->address;
            $user->save();

            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', 'Please Enter the Details');
        }
    }

    private function convertCart()
    {
        $contents = \Cart::getContent();
        foreach ($contents as $content) {
            $cartcont = new \App\Http\Controllers\Front\CartController();
            $price = $cartcont->planCost($content->id, \Auth::user()->id);
            if ($content->attributes->domain!="") {
                $price = $price * $content->attributes->agents;
            }
            \Cart::update($content->id, [
                'price'      => $price,
                'attributes' => [
                    'currency' => getCurrencyForClient(\Auth::user()->country),
                    'symbol' => \App\Model\Payment\Currency::where('code', getCurrencyForClient(\Auth::user()->country))->value('symbol'),
                    'agents' => $content->attributes->agents,
                    'domain' => $content->attributes->domain,
                ],
            ]);
        }
        Session::forget('toggleState');
    }
}
