<?php

namespace App\Http\Controllers\Auth;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Common\Bussiness;
use App\Model\Common\StatusSetting;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
            $location = getLocation();

            return view('themes.default1.front.auth.login-register', compact('bussinesses', 'location', 'status', 'apiKeys'));
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            $error = $ex->getMessage();
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email1' => 'required',
            'password1' => 'required',
            'g-recaptcha-response' => 'sometimes|required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Robot Verification Failed. Please Try Again.',
            'email1.required'    => 'Username/Email is required',
            'password1.required' => 'Password is required',
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
                            ->withInput($request->only('email1', 'remember'))
                            ->withErrors([
                                'email1' => 'Invalid Email and/or Password',
                            ]);
            }

            if (! \Hash::check($password, $user->password)) {//Check for correct password
                return redirect()->back()
                ->withInput($request->only('email1', 'remember'))
                ->withErrors([
                    'email1' => 'Invalid Email and/or Password',
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

        return redirect()->intended($this->redirectPath());
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
            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
        }
    }
}
