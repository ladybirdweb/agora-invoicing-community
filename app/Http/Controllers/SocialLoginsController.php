<?php

namespace App\Http\Controllers;

use App\SocialLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

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
        try {
            SocialLogin::where('type', $request->type)->update([
                'client_id' => $request->client_id,
                'client_secret' => $request->client_secret,
                'redirect_url' => $request->redirect_url,
                'status' => $request->optradio,
            ]);

            Session::flash('success', Lang::get('message.social_succes'));
        } catch (\Exception $e) {
            Session::flash('error', Lang::get('message.social_fail'));
        }

        return redirect()->back();
    }
}
