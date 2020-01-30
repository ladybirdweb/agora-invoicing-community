<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

trait SendsPasswordResetEmails {

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm() {
        return view('themes.default1.front.auth.password');
    }

    public function getEmail() {
        try {
            return view('themes.default1.front.auth.password');
        } catch (\Exception $ex) {
            return redirect()->with('fails', $ex->getMessage());
        }
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param string $token
     *
     * @return Response
     */
    public function getReset($token = null) {
        if (is_null($token)) {
            throw new NotFoundHttpException();
        }

        return view('themes.default1.front.auth.reset')->with('token', $token);
    }

    /**
     * Reset the given user's password.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postReset(Request $request) {
        //dd($request->input('token'));
        $this->validate($request, [
            'token' => 'required',
            //'email' => 'required|email',
            'password' => 'required|confirmed',
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
                                    'email' => 'Invalid email',]);
            }
        } else {
            return redirect()->back()
                            ->withInput($request->only('email'))
                            ->withErrors([
                                'email' => 'Invalid email',]);
        }
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request) {
        try{
        $this->validate($request, ['email' => 'required|email|exists:users,email']);
        $email = $request->email;
        $token = str_random(40);
        $password = new \App\Model\User\Password();
        if ($password->where('email', $email)->first()) {
            $token = $password->where('email', $email)->first()->token;
        } else {
            $activate = $password->create(['email' => $email, 'token' => $token]);
            $token = $activate->token;
        }

        $url = url("password/reset/$token");

        $user = new \App\User();
        $user = $user->where('email', $email)->first();
        if (!$user) {
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
        $replace = ['name' => $user->first_name . ' ' . $user->last_name, 'url' => $url,'contact_us'=>$contactUs];
        $type = '';


        if ($template) {
            $type_id = $template->type;
            $temp_type = new \App\Model\Common\TemplateType();
            $type = $temp_type->where('id', $type_id)->first()->name;
        }
        $templateController = new \App\Http\Controllers\Common\TemplateController();
        $mail = $templateController->mailing($from, $to, $data, $subject, $replace, $type);
         $response = ['type' => 'success',   'message' =>'Reset instructions have been mailed to ' . $to .'
        .Be sure to check your Junk folder if you do not see an email from us in your Inbox within a few minutes.'];

            return response()->json($response);
      }
      catch (\Exception $ex) {
            $result = [$ex->getMessage()];
            $errors = ['If you are registered with the entered email, reset instructions have been mailed to you
        .Be sure to check your Junk folder if you do not see an email from us in your Inbox within a few minutes.'];
            return response()->json(compact('result','errors'), 500);
        }

    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(Request $request) {
        $this->validate($request, ['email' => 'required|email']);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse($response) {
        return back()->with('status', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response) {
        return back()->withErrors(
                        ['email' => trans($response)]
        );
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker() {
        return Password::broker();
    }

}
