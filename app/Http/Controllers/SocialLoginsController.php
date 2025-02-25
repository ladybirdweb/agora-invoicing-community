<?php

namespace App\Http\Controllers;

use App\SocialLogin;
use Illuminate\Http\Request;
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
<<<<<<< HEAD
        if ($request->type == 'Twitter') {
=======

        if($request->type=='Twitter'){
>>>>>>> 65e62e04e (fixes)
            $request->validate([
                'api_key' => 'required',
                'api_secret' => 'required',
            ]);

            try {
                SocialLogin::where('type', $request->type)->update([
                    'client_id' => $request->api_key,
                    'client_secret' => $request->api_secret,
                    'redirect_url' => $request->redirect_url,
                    'status' => $request->optradio,
                ]);
<<<<<<< HEAD

                Session::flash('success', 'Social login settings updated successfully');
            } catch (\Exception $e) {
                Session::flash('error', 'An error occurred while updating social login settings');
            }
        } else {
            $request->validate([
                'client_id' => 'required',
                'client_secret' => 'required',
            ]);

            try {
                SocialLogin::where('type', $request->type)->update([
                    'client_id' => $request->client_id,
                    'client_secret' => $request->client_secret,
                    'redirect_url' => $request->redirect_url,
                    'status' => $request->optradio,
                ]);

                Session::flash('success', 'Social login settings updated successfully');
            } catch (\Exception $e) {
                Session::flash('error', 'An error occurred while updating social login settings');
            }
        }
=======
>>>>>>> 65e62e04e (fixes)

                Session::flash('success', 'Social login settings updated successfully');
            } catch (\Exception $e) {
                Session::flash('error', 'An error occurred while updating social login settings');
            }
        }else {
            $request->validate([
                'client_id' => 'required',
                'client_secret' => 'required',
            ]);

            try {
                SocialLogin::where('type', $request->type)->update([
                    'client_id' => $request->client_id,
                    'client_secret' => $request->client_secret,
                    'redirect_url' => $request->redirect_url,
                    'status' => $request->optradio,
                ]);

                Session::flash('success', 'Social login settings updated successfully');
            } catch (\Exception $e) {
                Session::flash('error', 'An error occurred while updating social login settings');
            }
        }
        return redirect()->back();
    }
}
