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

 public function view()
 {
     $socialLoginss = SocialLogin::get();
     return view('themes.default1.common.socialLogins', compact('socialLoginss'));
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
        $socialLogins->status = $request->optradio;
        $socialLogins->save();
         return redirect()->back();
    }
}
