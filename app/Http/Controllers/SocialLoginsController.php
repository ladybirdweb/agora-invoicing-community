<?php

namespace App\Http\Controllers;

use App\SocialLogin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest')->only('redirectToGithub');
        $this->middleware('guest')->only('handler');
    }

    public function redirectToGithub($provider)
    {
        $details = SocialLogin::where('type', $provider)->first();
        \Config::set("services.$provider.redirect", $details->redirect_url);
        \Config::set("services.$provider.client_id", $details->client_id);
        \Config::set("services.$provider.client_secret", $details->client_secret);

        return Socialite::driver($provider)->redirect();
    }

    public function handler($provider)
    {
        $details = SocialLogin::where('type', $provider)->first();
        \Config::set("services.$provider.redirect", $details->redirect_url);
        \Config::set("services.$provider.client_id", $details->client_id);
        \Config::set("services.$provider.client_secret", $details->client_secret);
        $githubUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate([
            'email' => $githubUser->getemail(),
        ],
            [
                'user_name' => $githubUser->getNickName(),
                'first_name' => $githubUser->getname(),
                'role' => 'user',
                'password' => Hash::make(Str::random()),
                'mobile_verified' => '1',
                'active' => '1',
            ]);

        \Auth::login($user);

        return redirect('/');
        //  return redirect($this->redirectPath());
    }

//
 public function view()
 {
     $socialLogins = SocialLogin::get();

     return view('themes.default1.common.socialLogins', compact('socialLogins'));
 }

    public function edit($id)
    {
        $socialLogins = SocialLogin::where('id', $id)->first();

        return view('themes.default1.common.editSocialLogins', compact('socialLogins'));
    }

    public function update(Request $request)
    {
        $socialLogins = SocialLogin::where('type', $request->type)->first();
        $socialLogins->type = $request->type;
        $socialLogins->client_id = $request->client_id;
        $socialLogins->client_secret = $request->client_secret;
        $socialLogins->redirect_url = $request->redirect_url;
        $socialLogins->save();

        return redirect()->back();
    }

         public function mobileVerification()
         {
             //  dd('ghj');
             //   $socialLogins = SocialLogin::get();
             return view('themes.default1.front.auth.mobileVerification'
                 // ,compact('socialLogins')
             );
         }
}
