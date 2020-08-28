<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('themes.default1.front.auth.password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $this->validate($request, ['email' => 'required|email|exists:users,email']);
            $email = $request->email;
            $token = str_random(40);
            $password = new \App\Model\User\Password();
            if ($password->where('email', $email)->first()) {
                $password->where('email', $email)->update(['created_at'=>\Carbon\Carbon::now()]);
                $token = $password->where('email', $email)->first()->token;
            } else {
                $activate = $password->create(['email' => $email, 'token' => $token, 'created_at'=>\Carbon\Carbon::now()]);
                $token = $activate->token;
            }

            $url = url("password/reset/$token");

            $user = new \App\User();
            $user = $user->where('email', $email)->first();
            if (! $user) {
                return redirect()->back()->with('fails', 'Invalid Email');
            }
            //check in the settings
            $settings = new \App\Model\Common\Setting();
            $setting = $settings->where('id', 1)->first();
            //template
            $templates = new \App\Model\Common\Template();
            $temp_id = $setting->forgot_password;
            $template = $templates->where('id', $temp_id)->first();

            $from = $setting->email;
            $to = $user->email;
            $contactUs = $setting->website;
            $subject = $template->name;
            $data = $template->data;
            $replace = ['name' => $user->first_name.' '.$user->last_name, 'url' => $url, 'contact_us'=>$contactUs];
            $type = '';

            if ($template) {
                $type_id = $template->type;
                $temp_type = new \App\Model\Common\TemplateType();
                $type = $temp_type->where('id', $type_id)->first()->name;
            }
            $templateController = new \App\Http\Controllers\Common\TemplateController();
            $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);
            $response = ['type' => 'success',   'message' =>'Reset instructions have been mailed to '.$to.'
    .Be sure to check your Junk folder if you do not see an email from us in your Inbox within a few minutes.'];

            return response()->json($response);
        } catch (\Exception $ex) {
            $result = [$ex->getMessage()];
            $errors = ['If you are registered with the entered email, reset instructions have been mailed to you. '];

            return response()->json(compact('result', 'errors'), 500);
        }
    }
}
