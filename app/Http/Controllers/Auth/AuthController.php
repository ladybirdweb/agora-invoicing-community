<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Model\User\AccountActivate;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */

use AuthenticatesAndRegistersUsers;

    /* to redirect after login */

    protected $redirectTo = 'home';

    /* Direct After Logout */
    protected $redirectAfterLogout = 'home';

    protected $loginPath = 'auth/login';

    //protected $loginPath = 'login';

    /**
     * Create a new authentication controller instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard     $auth
     * @param \Illuminate\Contracts\Auth\Registrar $registrar
     *
     * @return void
     */
    public function __construct(Guard $auth, Registrar $registrar)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        try {
            return view('themes.default1.front.auth.login');
        } catch (\Exception $ex) {
            dd($ex);
            //return redirect('home')->with('fails',$ex->getMessage());
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
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $credentials = ['email' => $email, 'password' => $password, 'active' => 1];

        //$credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials, $request->has('remember'))) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect()->back()
                        ->withInput($request->only('email', 'remember'))
                        ->withErrors([
                            'email'  => $this->getFailedLoginMessage(),
                            'active' => 'Please activate your account',
        ]);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.new_register');
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
        $pass = $request->input('password');
        $password = \Hash::make($pass);
        $user->password = $password;
        $user->role = 'user';
        $user->fill($request->except('password'))->save();
        $token = str_random(40);
        $activate->create(['email' => $user->email, 'token' => $token]);
        \Mail::send('emails.welcome', ['token' => $token, 'email' => $user->email, 'pass' => $pass], function ($message) use ($user) {
            $message->to($user->email, $user->first_name)->subject('Welcome!');
        });

        return redirect()->back()->with('success', \Lang::get('message.to-activate-your-account-please-click-on-the-link-that-has-been-send-to-your-email'));
    }

    public function Activate($token, AccountActivate $activate, Request $request, User $user)
    {
        if ($activate->where('token', $token)->first()) {
            $email = $activate->where('token', $token)->first()->email;
        } else {
            throw new NotFoundHttpException();
        }
        $user = $user->where('email', $email)->first();
        if ($user->where('email', $email)->first()) {
            $user->active = 1;
            $user->save();
            //\Auth::loginUsingId($user->id);
            return redirect('auth/login');
        } else {
            throw new NotFoundHttpException();
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
}
