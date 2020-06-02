<?php

namespace Illuminate\Foundation\Auth;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

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
            $userId = \Session::get('2fa:user:id');
            $user = User::find($userId);
            if ($user && $user->is_2fa_enabled) {
                \Session::put('reset_token',$token);
                return redirect('verify-2fa');
            }
            return view('themes.default1.front.auth.reset')->with(
                ['reset_token' => $token, 'email' => $request->email]
            );
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
                //'email' => 'required|email',
                'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|
               confirmed',
            ],
                ['password.regex'=>'Your password must be more than 6 characters long, should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.']);
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
                                        'email' => 'User cannot be identified', ]);
                }
            } else {
                return redirect()->back()
                                ->withInput($request->only('email'))
                                ->withErrors([
                                    'email' => 'Invalid token', ]);
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
