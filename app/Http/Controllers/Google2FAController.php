<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateSecretRequest;
use App\User;
use Crypt;
use Google2FA;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use ParagonIE\ConstantTime\Base32;

class Google2FAController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    public function verify2fa()
    {
        if (\Session::has('2fa:user:id')) {
            return view('themes.default1.front.enableTwoFactor');
        } else {
            return redirect()->back();
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        //generate new secret
        $secret = $this->generateSecret();

        //get user
        $user = $request->user();

        //encrypt and then save secret
        $user->google2fa_secret = Crypt::encrypt($secret);
        $user->save();

        //generate image for QR barcode
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->email,
            $secret,
            200
        );

        return successResponse('', ['image' => $imageDataUri, 'secret' => $secret]);
    }

    /**
     * Generate a secret key in Base32 format.
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function getValidateToken()
    {
        if (session('2fa:user:id')) {
            return redirect('verify-2fa');
        }

        return redirect('login');
    }

    /**
     * @param  App\Http\Requests\ValidateSecretRequest $request
     * @return \Illuminate\Http\Response
     */
    public function postLoginValidateToken(ValidateSecretRequest $request)
    {
        //get user id and create cache key
        $userId = $request->session()->pull('2fa:user:id');
        $this->user = User::findorFail($userId);
        $secret = Crypt::decrypt($this->user->google2fa_secret);
        $checkValidPasscode = Google2FA::verifyKey($secret, $request->totp);

        //login and redirect user
        if ($checkValidPasscode) {
            if (\Session::has('reset_token')) {
                $token = \Session::get('reset_token');
                \Session::put('2fa_verified', 1);
                \Session::forget('2fa:user:id');

                return redirect('password/reset/'.$token);
            }
            \Auth::loginUsingId($userId);

            return redirect()->intended($this->redirectPath());
        } else {
            \Session::put('2fa:user:id', $userId);

            return redirect('verify-2fa')->with('fails', 'Invalid Code');
        }
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

    public function verifyPassword(Request $request)
    {
        $user = \Auth::user();
        if (\Hash::check($request->input('user_password'), $user->getAuthPassword())) {
            return successResponse('password_verified');
        } else {
            return errorResponse('password_incorrect');
        }
    }

    public function postSetupValidateToken(Request $request)
    {
        $user = $request->user();
        $secret = Crypt::decrypt($user->google2fa_secret);
        $checkValidPasscode = Google2FA::verifyKey($secret, $request->totp);
        if ($checkValidPasscode == true) {
            $user->is_2fa_enabled = 1;
            $user->google2fa_activation_date = \Carbon\Carbon::now();
            $user->save();

            return successResponse(\Lang::get('message.valid_passcode'));
        }

        return errorResponse(\Lang::get('message.invalid_passcode'));
    }

    /**
     * Disables 2FA for a user/agent, wipes out all the details related to 2FA from the Database.
     *
     * @param \Illuminate\Http\Request $request
     * @return json \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->userId ? User::where('id', $request->userId)->first() : $request->user();
        if (\Auth::user()->role != 'admin' && $user->id != \Auth::user()->id) {
            return errorResponse('Cannot disable 2FA. Invalid modification of data');
        }
        //make secret column blank
        $user->google2fa_secret = null;
        $user->google2fa_activation_date = null;
        $user->is_2fa_enabled = 0;
        $user->backup_code = null;
        $user->code_usage_count = 0;
        $user->save();

        return successResponse(\Lang::get('message.2fa_disabled'));
    }

    public function generateRecoveryCode()
    {
        $code = str_random(20);
        User::where('id', \Auth::user()->id)->update(['backup_code'=>$code, 'code_usage_count'=>0]);

        return successResponse(['code'=>$code]);
    }

    public function getRecoveryCode()
    {
        $code = User::find(\Auth::user()->id)->backup_code;

        return successResponse(['code'=>$code]);
    }

    public function showRecoveryCode()
    {
        if (session('2fa:user:id')) {
            return view('themes.default1.front.recoveryCode');
        }

        return redirect('login');
    }

    public function verifyRecoveryCode(Request $request)
    {
        $this->validate($request,[
            'rec_code'=>'required',
        ],
        ['rec_code.required'=>'Plase enter recovery code',
        ]);
        try {
            $userId = $request->session()->pull('2fa:user:id');
            $this->user = User::findorFail($userId);
            if ($this->user->code_usage_count == 1) {//If backup code is used already
                throw new \Exception('This code is already used once. Please use Authenticator app to enter the code or contact admin for disabling 2FA for your account.');
            }
            $rec_code = $request->input('rec_code');
            if ($rec_code == $this->user->backup_code) {
                $this->user->code_usage_count = 1;
                $this->user->save();
                \Auth::loginUsingId($userId);

                return redirect()->intended($this->redirectPath());
            } else {
                \Session::put('2fa:user:id', $userId);
                throw new \Exception('Invalid recovery code.');
            }
        } catch (\Exception $e) {
            \Session::put('2fa:user:id', $userId);

            return redirect('recovery-code')->with('fails', $e->getMessage());
        }
    }
}
