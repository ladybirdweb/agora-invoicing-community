<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Symfony\Component\Mime\Email;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        
            //check in the settings
            $settings = new \App\Model\Common\Setting();
            $setting = $settings->where('id', 1)->first();
            //template
            $templates = new \App\Model\Common\Template();
            $temp_id = $setting->forgot_password;
            $template = $templates->where('id', $temp_id)->first();
            
            $mail = new \App\Http\Controllers\Common\PhpMailController();
            $mailer = $mail->setMailConfig($setting);
            $html = $template->data;
            
            $this->validate($request, ['email' => 'required|email|exists:users,email']);
            $email = $request->email;
            $user = new \App\User();
            $user = $user->where('email', $email)->first();
        try {
           
           
           
            $token = str_random(40);
            $password = new \App\Model\User\Password();
            if ($password->where('email', $email)->first()) {
                $password->where('email', $email)->update(['created_at' => \Carbon\Carbon::now()]);
                $token = $password->where('email', $email)->first()->token;
            } else {
                $activate = $password->create(['email' => $email, 'token' => $token, 'created_at' => \Carbon\Carbon::now()]);
                $token = $activate->token;
            }

            $url = url("password/reset/$token");

             $to = $user->email;
            if (! $user) {
                return redirect()->back()->with('fails', 'Invalid Email');
            }
          
             $contactUs = $setting->website;


            if (emailSendingStatus()) {
                 $email = (new Email())
                 ->from($setting->email)
                 ->to($user->email)
                 ->subject($template->name)
                 ->html($mail->mailTemplate($template->data,$templatevariables=[ 'name' => $user->first_name.' '.$user->last_name, 'url' => $url, 'contact_us' => $contactUs]));
               
                 $mailer->send($email); 
                 $mail->email_log_success($setting->email,$user->email,$template->name,$html);
                $response = ['type' => 'success',   'message' => 'Reset instructions have been mailed to '.$to.'
             .Be sure to check your Junk folder if you do not see an email from us in your Inbox within a few minutes.'];
            } else {
                $response = ['type' => 'fails',   'message' => 'System email is not configured. Please contact admin.'];
            }

            return response()->json($response);
        } catch (\Exception $ex) {
            
                $mail->email_log_fail($setting->email,$user->email,$template->name,$html);
          
            // dd($ex,$ex->getCode());
            if ($ex instanceof \Illuminate\Validation\ValidationException) {
                $errors = ['Reset instructions have been mailed to you.
                .Be sure to check your Junk folder if you do not see an email from us in your Inbox within a few minutes. '];
            } else {
                $errors = ['System email is not configured. Please contact admin.'];
            }
            $result = [$ex->getMessage()];

            return response()->json(compact('result', 'errors'), 500);
        }
    }
}
