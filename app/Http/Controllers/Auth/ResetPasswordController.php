<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
        return view('themes.default1.front.auth.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
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
            //'email' => 'required|email',
            'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|
               confirmed',
        ]);
        $token = $request->input('token');
        $pass = $request->input('password');
        $password = new \App\Model\User\Password();
        $password = $password->where('token', $token)->first();
        if ($password) {
            $user = new \App\User();
            $user = $user->where('email', $password->email)->first();
            if ($user) {
                $user->password = \Hash::make($pass);
                $user->save();

                return redirect('auth/login')->with('success', 'You have successfully changed your password');
            } else {
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'Invalid email', ]);
            }
        } else {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Invalid email', ]);
        }


    }
}
