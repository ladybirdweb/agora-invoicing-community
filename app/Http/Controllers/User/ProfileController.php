<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function Profile()
    {
        try {
            $user = \Auth::user();
            $timezones = new \App\Model\Common\Timezone();
            $timezones = $timezones->lists('name', 'id')->toArray();
            $state = \App\Http\Controllers\Front\CartController::getStateByCode($user->state);
            $states = \App\Http\Controllers\Front\CartController::findStateByRegionId($user->country);
            $bussinesses = \App\Model\Common\Bussiness::lists('name', 'short')->toArray();

            return view('themes.default1.user.profile', compact('bussinesses', 'user', 'timezones', 'state', 'states'));
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function UpdateProfile(ProfileRequest $request)
    {
        try {
            $user = \Auth::user();
            if ($request->hasFile('profile_pic')) {
                $name = \Input::file('profile_pic')->getClientOriginalName();
                $destinationPath = 'dist/app/users';
                $fileName = rand(0000, 9999).'.'.$name;
                \Input::file('profile_pic')->move($destinationPath, $fileName);
                $user->profile_pic = $fileName;
            }
            $user->fill($request->input())->save();

            return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }

    public function UpdatePassword(ProfileRequest $request)
    {
        try {
            $user = \Auth::user();
            $oldpassword = $request->input('old_password');
            $currentpassword = $user->getAuthPassword();
            $newpassword = $request->input('new_password');
            if (Hash::check($oldpassword, $currentpassword)) {
                $user->password = Hash::make($newpassword);
                $user->save();

                return redirect()->back()->with('success1', \Lang::get('message.updated-successfully'));
            } else {
                return redirect()->back()->with('fails1', \Lang::get('message.not-updated'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('fails', $e->getMessage());
        }
    }
}
