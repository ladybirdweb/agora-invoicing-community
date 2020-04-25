<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Common\StatusSetting;
use App\ApiKey;
use Illuminate\Validation\ValidationException;
use Bugsnag;
use App\User;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Spatie\Activitylog\Models\Activity;

use Validator;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
       try{
           $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();
           $cont = new \App\Http\Controllers\Front\PageController();
           $captchaStatus = StatusSetting::pluck('recaptcha_status')->first();
           $captchaSiteKey = ApiKey::pluck('nocaptcha_sitekey')->first();
           $captchaSecretKey = ApiKey::pluck('captcha_secretCheck')->first();
           $mobileStatus = StatusSetting::pluck('msg91_status')->first();
           $msg91Key = ApiKey::pluck('msg91_auth_key')->first(); 
           $emailStatus = StatusSetting::pluck('emailverification_status')->first();
           $termsStatus = StatusSetting::pluck('terms')->first();
           $termsUrl = ApiKey::pluck('terms_url')->first(); 
            $location = $cont->getLocation();
         return view('themes.default1.front.auth.login-register', compact('bussinesses','location','captchaStatus','captchaSiteKey','captchaSecretKey','mobileStatus','msg91Key','emailStatus','termsStatus','termsUrl'));

          }catch(\Exception $ex){
            app('log')->error($ex->getMessage());
            Bugsnag::notifyException($ex);
        
             $error = $ex->getMessage();

         }
    }


         /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {//here
          $this->validate($request, [
            'email1' => 'required', 'password1' => 'required',
            'g-recaptcha-response' => 'sometimes|required|captcha'
                ], [
            'g-recaptcha-response.required' => 'Robot Verification Failed. Please Try Again.',
            'email1.required'    => 'Username/Email is required',
            'password1.required' => 'Password is required',
        ]);
       $usernameinput = $request->input('email1');
        $password = $request->input('password1');
        $credentialsForEmail = ['email' => $usernameinput, 'password' => $password,'active' => '1' , 'mobile_verified' => '1'];
         $auth = \Auth::attempt($credentialsForEmail, $request->has('remember'));
           if (!$auth) {
            $credentialsForusername = ['user_name' => $usernameinput, 'password' => $password,'active' => '1' , 'mobile_verified' => '1'];
            $auth = \Auth::attempt($credentialsForusername, $request->has('remember'));
        } if(!$auth){
            $user = User::where('email', $usernameinput)->orWhere('user_name', $usernameinput)->first();
           if($user==null){
                        return redirect()->back()
                            ->withInput($request->only('email1', 'remember'))
                            ->withErrors([
                                'email1' => 'Invalid Email and/or Password',
            ]);  
            } 
            if(!Hash::check($password ,$user->password)){
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
           if (Auth::user()->is_2fa_enabled ==1) {
                    $userId = Auth::user()->id;
                    Auth::logout();
                    $request->session()->put('2fa:user:id', $userId);
                    return redirect('2fa/validate');
                }
                 activity()->log('Logged In');
                 return redirect()->intended($this->redirectPath());
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
