<?php

namespace App\Http\Controllers\Auth;

use App\ApiKey;
use App\Http\Controllers\Controller;
use App\Model\Common\StatusSetting;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        try {
            $reset = \DB::table('password_resets')->select('email', 'created_at')->where('token', $token)->first();
            if ($reset) {
                if (Carbon::parse($reset->created_at)->addSeconds(config('auth.passwords.users.expire') * 60) > Carbon::now()) {
                    $captchaStatus = StatusSetting::pluck('recaptcha_status')->first();
                    $captchaKeys = ApiKey::select('nocaptcha_sitekey', 'captcha_secretCheck')->first();
                    $captchaSecretKey = ApiKey::pluck('captcha_secretCheck')->first();
                    $user = User::where('email', $reset->email)->first();
                    if ($user && $user->is_2fa_enabled && \Session::get('2fa_verified') == null) {
                        \Session::put('2fa:user:id', $user->id);
                        \Session::put('reset_token', $token);

                        return redirect('verify-2fa');
                    }

                    return view('themes.default1.front.auth.reset', compact('captchaKeys', 'captchaStatus'))->with(
                ['reset_token' => $token, 'email' => $reset->email]
            );
                } else {
                    return \Redirect::to('password/reset')->with('fails', 'It looks like you clicked on an invalid password reset link. Please try again.');
                }
            } else {
                return redirect('password/reset')->with('fails', 'Reset password token expired or not found. Please try again');
            }
        } catch (\Exception $ex) {
            return redirect('login')->with('fails', $ex->getMessage());
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
       confirmed', 'g-recaptcha-response' => 'sometimes|required|captcha',
        ], ['password.regex'=>'Your password must be more than 6 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.', 'g-recaptcha-response.required'=>'Please verify that you are not a robot.',
            'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
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
                } else {
                    return redirect()->back()
                            ->withInput($request->only('email'))
                            ->with('fails', 'Cannot reset password. Invalid modification of data suspected.');
                }
            } else {
                return redirect()->back()
                        ->withInput($request->only('email'))
                        ->with('fails', 'Cannot reset password.');
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }
}
