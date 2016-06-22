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

    //protected $redirectTo = 'home';

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
            return view('themes.default1.front.auth.login-register');
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
            'email1' => 'required', 'password1' => 'required',
                ], [
            'email1.required'    => 'Username/Email is required',
            'password1.required' => 'Password is required',
        ]);
        $usernameinput = $request->input('email1');
        //$email = $request->input('email');
        $field = filter_var($usernameinput, FILTER_VALIDATE_EMAIL) ? 'email' : 'user_name';
        $password = $request->input('password1');
        $credentials = [$field => $usernameinput, 'password' => $password, 'active' => 1];

        //$credentials = $request->only('email', 'password');
        $auth = \Auth::attempt($credentials, $request->has('remember'));

        if ($auth) {
            return redirect()->intended($this->redirectPath());
        }

        $user = new User();
        $user = $user->where('email', $usernameinput)->orWhere('user_name', $usernameinput)->first();
        if ($user) {
            if ($user->active != 1) {
                $url = url("resend/activation/$user->email");
                $error = "Before you can login, you must active your account with the code sent to your email address.
                If you did not receive this email, please check your junk/spam folder.
<a href=$url>Click here</a> to resend the activation email.";
                //$error = "Please activate your account, or <a href=$url>resent the activation link</a>";
            } else {
                $error = 'Email and/or password invalid.';
            }
        } else {
            $error = 'Email and/or password invalid.';
        }

        return redirect()->back()
                        ->withInput($request->only('email1', 'remember'))
                        ->withErrors([
                            'email1' => $error,
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
        try {
            $pass = $request->input('password');
            $currency = 'INR';
            $location = \GeoIP::getLocation();
            if ($location['country'] == 'India') {
                $currency = 'INR';
            } else {
                $currency = 'USD';
            }
            if (\Session::has('currency')) {
                $currency = \Session::get('currency');
            }
            $password = \Hash::make($pass);
            $user->password = $password;
            $user->role = 'user';
            $user->currency = $currency;
            $user->timezone_id = \App\Http\Controllers\Front\CartController::getTimezoneByName($location['timezone']);
            $user->fill($request->except('password'))->save();
            $this->sendActivation($user->email, $request->method(), $pass);
            //$result = ['success' => \Lang::get('message.to-activate-your-account-please-click-on-the-link-that-has-been-send-to-your-email')];
            return redirect()->back()->with('success', \Lang::get('message.to-activate-your-account-please-click-on-the-link-that-has-been-send-to-your-email'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
            //$result = ['fails' => $ex->getMessage()];
        }

        //return response()->json(compact('result'));
    }

    public function sendActivationByGet($email, Request $request)
    {
        try {
            $mail = $this->sendActivation($email, $request->method());
            if ($mail == 'success') {
                return redirect('auth/login')->with('success', 'Activation link has sent to your email address');
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('fails', $ex->getMessage());
        }
    }

    public function sendActivation($email, $method, $str = '')
    {
        try {
            $user = new User();
            $activate_model = new AccountActivate();
            $user = $user->where('email', $email)->first();
            if (!$user) {
                return redirect()->back()->with('fails', 'Invalid Email');
            }
            //dd($request->method());
            if ($method == 'GET') {
                $activate_model = $activate_model->where('email', $email)->first();
                $token = $activate_model->token;
            } else {
                $token = str_random(40);
                $activate = $activate_model->create(['email' => $email, 'token' => $token]);
                $token = $activate->token;
            }
            $url = url("activate/$token");
            //check in the settings
            $settings = new \App\Model\Common\Setting();
            $settings = $settings->where('id', 1)->first();
            //template
            $template = new \App\Model\Common\Template();
            $temp_id = $settings->where('id', 1)->first()->welcome_mail;
            $template = $template->where('id', $temp_id)->first();
            $from = $settings->email;
            $to = $user->email;
            $subject = $template->name;
            $data = $template->data;
            $replace = ['name' => $user->first_name.' '.$user->last_name, 'username' => $user->email, 'password' => $str, 'url' => $url];
            $type = '';
            if ($template) {
                $type_id = $template->type;
                $temp_type = new \App\Model\Common\TemplateType();
                $type = $temp_type->where('id', $type_id)->first()->name;
            }
            //dd($type);
            $templateController = new \App\Http\Controllers\Common\TemplateController();
            $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);

            return $mail;
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage());
        }
    }

    public function Activate($token, AccountActivate $activate, Request $request, User $user)
    {
        try {
            if ($activate->where('token', $token)->first()) {
                $email = $activate->where('token', $token)->first()->email;
            } else {
                throw new NotFoundHttpException();
            }
            //dd($email);
            $url = 'auth/login';
            $user = $user->where('email', $email)->first();
            if ($user->where('email', $email)->first()) {
                $user->active = 1;
                $user->save();

                $mailchimp = new \App\Http\Controllers\Common\MailChimpController();
                $r = $mailchimp->addSubscriber($user->email);
                if (\Session::has('session-url')) {
                    $url = \Session::get('session-url');

                    return redirect($url);
                }

                return redirect($url)->with('success', 'Email verification successful, Please login to access your account');
            } else {
                throw new NotFoundHttpException();
            }
        } catch (\Exception $ex) {
            if ($ex->getCode() == 400) {
                return redirect($url)->with('success', 'Email verification successful, Please login to access your account');

                return redirect($url);
            }

            return redirect($url)->with('fails', $ex->getMessage());
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
            return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
        }
    }
}
