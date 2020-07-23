<?php

namespace Illuminate\Foundation\Auth;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\ApiKey;
use Carbon\Carbon;
use App\Model\Common\StatusSetting;
use Illuminate\Auth\Events\PasswordReset;
use App\Model\User\AccountActivate;

trait ResetsPasswords
{
    use RedirectsUsers;

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        try {
        $reset = \DB::table('password_resets')->select('email','created_at')->where('token', '=', $token)->first();
        if ($reset) {
        if(Carbon::parse($reset->created_at)->addSeconds(config('auth.passwords.users.expire')*60) > Carbon::now()) {
            
            $captchaStatus = StatusSetting::pluck('recaptcha_status')->first();
            $captchaKeys = ApiKey::select('nocaptcha_sitekey','captcha_secretCheck')->first();
            $captchaSecretKey = ApiKey::pluck('captcha_secretCheck')->first();
            $user = User::where('email',$reset->email)->first();
            if ($user && $user->is_2fa_enabled && \Session::get('2fa_verified') == null) {
                \Session::put('2fa:user:id',$user->id);
                \Session::put('reset_token',$token);
                return redirect('verify-2fa');
            }
            return view('themes.default1.front.auth.reset',compact('captchaKeys','captchaStatus'))->with(
                ['reset_token' => $token, 'email' => $reset->email]
            );
        } else {
            return \Redirect::to('password/email')->with('fails', 'It looks like you clicked on an invalid password reset link. Please try again.');
        }
    } else {
        return redirect('password/email')->with('fails','Reset password token expired or not found. Please try again');
    }
       
        } catch (\Exception $ex) {
            return redirect('auth/login')->with('fails',$ex->getMessage());
        }

    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
        public function reset(Request $request)
        {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|
           confirmed', 'g-recaptcha-response' => 'sometimes|required|captcha'
        ], ['password.regex'=>'Your password must be more than 6 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.'
        ,'g-recaptcha-response.required'=>'Please verify that you are not a robot.',
        'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.'
        ]);
        try {
        $token = $request->input('token');
        $pass = $request->input('password');
        $email = $request->input('email');
        $password = new \App\Model\User\Password();
        $password_tokens = $password->where('email', '=', $email)->first();
         if ($password_tokens) { 
            if ($password_tokens->token == $token) { 
                    $user = new \App\User();
                    $user = $user->where('email', $email)->first();
                    if ($user) {
                        \Session::forget('2fa_verified');
                        \Session::forget('reset_token');

                        $user->password = \Hash::make($pass);
                        $user->save();
                        \DB::table('password_resets')->where('email', $user->email)->delete();
                        return redirect('login')->with('success', 'You have successfully changed your password');
                    } else {
                        return redirect()->back()
                                        ->withInput($request->only('email'))
                                        ->with('fails', 'User cannot be identified');
                    }
            }   else {
                return redirect()->back()
                                ->withInput($request->only('email'))
                                ->with('fails','Cannot reset password. Invalid modification of data suspected.');
            }
           
         } else {
            return redirect()->back()
                            ->withInput($request->only('email'))
                            ->with('fails','Cannot reset password.');
        }

        } catch(\Exception $ex) {
            return redirect()->back()->with('fails',$ex->getMessage());
        }
            



            // $this->validate($request, $this->rules(), $this->validationErrorMessages());

            // // Here we will attempt to reset the user's password. If it is successful we
            // // will update the password on an actual user model and persist it to the
            // // database. Otherwise we will parse the error and return the response.
            // $response = $this->broker()->reset(
            //     $this->credentials($request), function ($user, $password) {
            //         $this->resetPassword($user, $password);
            //     }
            // );

            // // If the password was successfully reset, we will redirect the user back to
            // // the application's home authenticated view. If there is an error we can
            // // redirect them back to where they came from with their error message.
            // return $response == Password::PASSWORD_RESET
            //             ? $this->sendResetResponse($response)
            //             : $this->sendResetFailedResponse($request, $response);
        }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [];
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return redirect($this->redirectPath())
                            ->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
