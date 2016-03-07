<?php

namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\ProfileRequest;

class ProfileController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function Profile() {
        $user = \Auth::user();
        return view('themes.default1.user.profile', compact('user'));
    }

    public function UpdateProfile(ProfileRequest $request) {
        $user = \Auth::user();
        if ($request->hasFile('profile_pic')) {
            $name = \Input::file('profile_pic')->getClientOriginalName();
            $destinationPath = 'dist/app/users';
            $fileName = rand(0000, 9999) . '.' . $name;
            \Input::file('profile_pic')->move($destinationPath, $fileName);
            $user->profile_pic = $fileName;
        }
        $user->fill($request->input())->save();
        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }

    public function UpdatePassword(ProfileRequest $request) {
        $user = \Auth::user();
        $oldpassword = $request->input('old_password');
        $currentpassword = $user->getAuthPassword();
        $newpassword = $request->input('new_password');
        if (Hash::check($oldpassword,$currentpassword)) {
            $user->password = Hash::make($newpassword);
            $user->save();
            return redirect()->back()->with('success1', \Lang::get('message.updated-successfully'));
        } else {
            return redirect()->back()->with('fails1', \Lang::get('message.not-updated'));
        }
    }

}
