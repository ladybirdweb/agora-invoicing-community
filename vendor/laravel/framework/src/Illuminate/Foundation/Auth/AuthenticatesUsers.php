<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\User;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

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
       $bussinesses = \App\Model\Common\Bussiness::pluck('name', 'short')->toArray();
        return view('themes.default1.front.auth.login-register', compact('bussinesses'));
    }


     /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        // dd('akdnj');
        $this->validate($request, [
            'email1' => 'required', 'password1' => 'required',
                ], [
            'email1.required'    => 'Username/Email is required',
            'password1.required' => 'Password is required',
        ]);
        $usernameinput = $request->input('email1');
        //$email = $request->input('email');
        $field = filter_var($usernameinput, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
        $password = $request->input('password1');
        $credentials = [$field => $usernameinput, 'password' => $password,'active' => '1' , 'mobile_verified' => '1'];
        //$credentials = $request->only('email', 'password');
        $auth = \Auth::attempt($credentials, $request->has('remember'));

         if (!$auth) {
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

        if ($user && (!$user->active || !$user->mobile_verified)) {
            return redirect('verify')->with('user', $user);
        }
            return redirect()->back()
                            ->withInput($request->only('email1', 'remember'))
                            ->withErrors([
                                'email1' => 'Invalid Email and/or Password',
            ]);
        }
       

       

            return redirect()->intended($this->redirectPath());

    }


//         /**
//      * Get the post register / login redirect path.
//      *
//      * @return string
//      */
//     public function redirectPath()
//     {  

// dd('op');
//         if (\Session::has('session-url')) {
//             $url = \Session::get('session-url');

//             return property_exists($this, 'redirectTo') ? $this->redirectTo : '/'.$url;
//         } else {
//             return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
//         }
//     }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
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
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
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
     * @throws ValidationException
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

        return redirect('/');
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
