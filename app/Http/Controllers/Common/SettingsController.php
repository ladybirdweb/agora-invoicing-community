<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\SettingRequest;
use App\Model\Common\Setting;
use App\Model\Common\Template;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function Settings(Setting $settings)
    {
        if (!$settings->where('id', '1')->first()) {
            $settings->create(['company' => '']);
        }
        $setting = $settings->where('id', '1')->first();
        $template = new Template();

        return view('themes.default1.common.settings', compact('setting', 'template'));
    }

    public function UpdateSettings(Setting $settings, SettingRequest $request)
    {
        //dd($request);
        $setting = $settings->where('id', '1')->first();
        if ($request->has('password')) {
            $encrypt = $request->input('password');
            $doencrypt = \Crypt::encrypt($encrypt);
            $setting->password = $doencrypt;
        }
        if ($request->hasFile('logo')) {
            $name = $request->file('logo')->getClientOriginalName();
            $destinationPath = public_path('cart/img/logo');
            $request->file('logo')->move($destinationPath, $name);
            $setting->logo = $name;
        }
        $setting->fill($request->except('password', 'logo'))->save();

        return redirect()->back()->with('success', \Lang::get('message.updated-successfully'));
    }
}
