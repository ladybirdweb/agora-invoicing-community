<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\CartController;
use App\Http\Requests\User\ProfileRequest;
use App\Model\Common\Country;
use App\Model\Common\Setting;
use App\Model\Common\StatusSetting;
use App\Model\User\AccountActivate;
use App\User;
use Facades\Spatie\Referer\Referer;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function postRegister(ProfileRequest $request, User $user, AccountActivate $activate)
    {
        try {
            $location = getLocation();
            $state_code = $location['iso_code'].'-'.$location['state'];
            $state = CartController::getStateByCode($state_code);
            $user->state = $state['id'];
            $password = \Hash::make($request->input('password'));
            $user->country = $request->input('country');
            $userCountry = Country::where('country_code_char2', $request->input('country'))->first();
            $user->currency = $this->getUserCurrency($userCountry);
            $user->currency_symbol = $this->getUserCurrencySymbol($userCountry);
            $user->password = $password;
            $user->town = $location['city'];
            $user->profile_pic = '';
            $user->active = 0;
            $user->mobile_verified = 0;
            $user->mobile = ltrim($request->input('mobile'), '0');
            $user->mobile_code = $request->input('mobile_code');
            $user->role = 'user';
            $user->first_name = strip_tags($request->input('first_name'));
            $user->last_name = strip_tags($request->input('last_name'));
            $user->company = strip_tags($request->input('company'));
            $user->address = strip_tags($request->input('address'));
            $user->email = strip_tags($request->input('email'));
            $user->user_name = strip_tags($request->input('email'));

            $user->manager = $user->assignSalesManager();
            $user->ip = $location['ip'];
            $user->referrer = Referer::get(); // 'google.com'
            $user->timezone_id = CartController::getTimezoneByName($location['timezone']);
            $user->save();
            $emailMobileStatusResponse = $this->getEmailMobileStatusResponse($user);

            activity()->log('User <strong>'.$user->first_name.' '.$user->last_name.'</strong> was created');

            return response()->json($emailMobileStatusResponse);
        } catch (\Exception $ex) {
            app('log')->error($ex->getMessage());
            $result = [$ex->getMessage()];

            return response()->json($result);
        }
    }

    protected function getEmailMobileStatusResponse($user)
    {
        $emailMobileSetting = StatusSetting::select('emailverification_status', 'msg91_status')->first();
        if ($emailMobileSetting->emailverification_status == 0 && $emailMobileSetting->msg91_status == 1) {
            $user->mobile_verified = 0;
            $user->active = 1;
            $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your Submission has been received successfully. Verify your Mobile to log into the Website.'];
        } elseif ($emailMobileSetting->emailverification_status == 1 && $emailMobileSetting->msg91_status == 0) {
            $user->mobile_verified = 1;
            $user->active = 0;
            $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your Submission has been received successfully. Verify your Email to log into the Website.'];
        } elseif ($emailMobileSetting->emailverification_status == 0 && $emailMobileSetting->msg91_status == 0) {
            $user->mobile_verified = 1;
            $user->active = 1;
            $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your have been Registered Successfully.'];
        } else {
            $response = ['type' => 'success', 'user_id' => $user->id, 'message' => 'Your Submission has been received successfully. Verify your Email and Mobile to log into the Website.'];
        }

        return $response;
    }

    protected function getUserCurrency($userCountry)
    {
        $currency = Setting::find(1)->default_currency;
        if ($userCountry->currency->status) {
            return $userCountry->currency->code;
        }

        return $currency;
    }

    protected function getUserCurrencySymbol($userCountry)
    {
        $currency_symbol = Setting::find(1)->default_symbol;
        if ($userCountry->currency->status) {
            return $userCountry->currency->symbol;
        }

        return $currency_symbol;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // return Validator::make($data, [
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users',
        //     'password' => 'required|string|min:6|confirmed',
        // ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        // return User::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => bcrypt($data['password']),
        // ]);
    }
}
